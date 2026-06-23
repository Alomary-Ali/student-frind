<?php

declare(strict_types=1);

namespace Tests\Feature\AppMiddleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use App\Http\Middleware\RoleMiddleware;
use Tests\TestCase;

final class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_correct_role_passes(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new RoleMiddleware();
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok'), 'student')
        );

        $response->assertOk();
        $response->assertSee('ok');
    }

    public function test_user_with_correct_role_passes_multiple_roles(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new RoleMiddleware();
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok'), 'student', 'admin')
        );

        $response->assertOk();
    }

    public function test_user_with_wrong_role_is_redirected_to_home(): void
    {
        $user = User::factory()->create(['role' => 'faculty']);
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        $middleware = new RoleMiddleware();
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok'), 'student')
        );

        $response->assertRedirect(route('home'));
    }

    public function test_guest_user_is_redirected_to_login(): void
    {
        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => null);

        $middleware = new RoleMiddleware();
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok'), 'student')
        );

        $response->assertRedirect(route('login'));
    }

    public function test_guest_user_with_json_expectation_gets_401(): void
    {
        $request = Request::create('/test', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $request->setUserResolver(fn () => null);

        $middleware = new RoleMiddleware();
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok'), 'student')
        );

        $response->assertStatus(401);
        $response->assertJson(['success' => false]);
    }

    public function test_user_with_wrong_role_and_json_expectation_gets_403(): void
    {
        $user = User::factory()->create(['role' => 'faculty']);
        $request = Request::create('/test', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $request->setUserResolver(fn () => $user);

        $middleware = new RoleMiddleware();
        $response = TestResponse::fromBaseResponse(
            $middleware->handle($request, fn ($req) => response('ok'), 'student')
        );

        $response->assertStatus(403);
        $response->assertJson(['success' => false]);
    }
}
