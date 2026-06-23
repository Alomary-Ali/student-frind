<?php

declare(strict_types=1);

namespace Modules\Shared\Tests\Feature\Infrastructure\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Modules\Shared\Domain\Contracts\RoleRepositoryInterface;
use Modules\Shared\Domain\Entities\Role;
use Modules\Shared\Domain\Enums\Role as RoleEnum;
use Modules\Shared\Domain\ValueObjects\Permission;
use Modules\Shared\Infrastructure\Middleware\PermissionMiddleware;
use Modules\Shared\Infrastructure\Middleware\RoleMiddleware;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class SharedMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_test/role', function () {
            return response()->json(['message' => 'ok']);
        })->middleware(RoleMiddleware::class . ':student');

        Route::get('/_test/permission', function () {
            return response()->json(['message' => 'ok']);
        })->middleware(PermissionMiddleware::class . ':students.view');
    }

    public function test_role_middleware_passes_with_correct_role(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440010',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '12345678',
        ]);

        $role = Role::create(RoleEnum::STUDENT);
        $roleRepo = $this->app->make(RoleRepositoryInterface::class);
        $roleRepo->save($role);

        $eloquentRole = \Modules\Shared\Infrastructure\Persistence\EloquentRole::find($role->id()->value());
        $eloquentRole->users()->attach($user->id);

        $this->actingAs($user);

        $response = $this->getJson('/_test/role');

        $response->assertStatus(200)
            ->assertJson(['message' => 'ok']);
    }

    public function test_role_middleware_returns_403_with_wrong_role(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440011',
            'email' => 'admin@test.com',
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'password_hash' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $role = Role::create(RoleEnum::ADMIN);
        $roleRepo = $this->app->make(RoleRepositoryInterface::class);
        $roleRepo->save($role);

        $eloquentRole = \Modules\Shared\Infrastructure\Persistence\EloquentRole::find($role->id()->value());
        $eloquentRole->users()->attach($user->id);

        $this->actingAs($user);

        $response = $this->getJson('/_test/role');

        $response->assertStatus(403);
    }

    public function test_role_middleware_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('/_test/role');

        $response->assertStatus(401);
    }

    public function test_permission_middleware_passes_with_correct_permission(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440012',
            'email' => 'advisor@test.com',
            'first_name' => 'Test',
            'last_name' => 'Advisor',
            'password_hash' => Hash::make('password'),
            'role' => 'advisor',
            'status' => 'active',
            'academic_id' => '87654321',
        ]);

        $role = Role::create(RoleEnum::ADVISOR);
        $role = $role->addPermission(Permission::of('students.view'));
        $roleRepo = $this->app->make(RoleRepositoryInterface::class);
        $roleRepo->save($role);

        $eloquentRole = \Modules\Shared\Infrastructure\Persistence\EloquentRole::find($role->id()->value());
        $eloquentRole->users()->attach($user->id);

        $this->actingAs($user);

        $response = $this->getJson('/_test/permission');

        $response->assertStatus(200)
            ->assertJson(['message' => 'ok']);
    }

    public function test_permission_middleware_returns_403_without_permission(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440013',
            'email' => 'student2@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '11111111',
        ]);

        $role = Role::create(RoleEnum::STUDENT);
        $roleRepo = $this->app->make(RoleRepositoryInterface::class);
        $roleRepo->save($role);

        $eloquentRole = \Modules\Shared\Infrastructure\Persistence\EloquentRole::find($role->id()->value());
        $eloquentRole->users()->attach($user->id);

        $this->actingAs($user);

        $response = $this->getJson('/_test/permission');

        $response->assertStatus(403);
    }

    public function test_permission_middleware_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('/_test/permission');

        $response->assertStatus(401);
    }
}
