<?php

declare(strict_types=1);

namespace Tests\Feature\AppMiddleware;

use App\Http\Middleware\TenantMiddleware;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

final class TenantMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_is_redirected_to_login(): void
    {
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => null);

        $middleware = new TenantMiddleware;
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok')),
        );

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_without_student_record_passes(): void
    {
        $user = User::factory()->create();
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new TenantMiddleware;
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok')),
        );

        $response->assertOk();
        $this->assertNull(app()->bound('tenant.id') ? app()->make('tenant.id') : null);
    }

    public function test_authenticated_user_with_student_record_sets_tenant_context(): void
    {
        $user = User::factory()->create();
        $studentId = (string) \Illuminate\Support\Str::uuid();
        \Illuminate\Support\Facades\DB::table('academic_students')->insert([
            'id' => $studentId,
            'user_id' => $user->id,
            'student_number' => 'S12345',
            'academic_status' => 'active',
            'academic_standing' => 'good',
            'cumulative_gpa' => 3.50,
            'institution_id' => 'inst-123',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new TenantMiddleware;
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok')),
        );

        $response->assertOk();
        $this->assertEquals('inst-123', app()->make('tenant.id'));
        $this->assertEquals('inst-123', $request->attributes->get('institution_id'));
    }
}
