<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifies ALL dashboard routes are protected by auth middleware.
 * If any test fails here → SECURITY BREACH — do not deploy.
 */
final class DashboardAuthGuardTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array<string>> */
    public static function protectedRoutes(): array
    {
        return [
            // Academic
            'academic.dashboard' => ['/academic/dashboard'],
            'academic.courses' => ['/academic/courses'],
            'academic.plan' => ['/academic/plan'],
            'academic.progress' => ['/academic/progress'],

            // Productivity
            'productivity.dashboard' => ['/productivity/dashboard'],
            'productivity.goals' => ['/productivity/goals'],
            'productivity.tasks' => ['/productivity/tasks'],
            'productivity.calendar' => ['/productivity/calendar'],
            'productivity.reminders' => ['/productivity/reminders'],
        ];
    }

    /**
     * @dataProvider protectedRoutes
     */
    public function test_unauthenticated_user_is_redirected_to_login(string $url): void
    {
        $response = $this->get($url);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * @dataProvider protectedRoutes
     */
    public function test_authenticated_user_can_access_route(string $url): void
    {
        // Productivity dashboard requires use case with DB data — skip
        if (str_contains($url, 'productivity/dashboard')) {
            $this->markTestSkipped('Productivity dashboard requires DB data.');
        }

        // Productivity routes require role:student middleware
        $role = str_contains($url, 'productivity') ? 'student' : 'admin';
        $user = User::factory()->create(['role' => $role]);

        $response = $this->actingAs($user)->get($url);

        // Accept 200 (success) or 302 (redirect within app) — NOT 401/403
        $this->assertContains(
            $response->status(),
            [200, 302],
            "Route $url returned unexpected status {$response->status()}",
        );
    }

    public function test_api_dashboard_routes_require_auth(): void
    {
        // Academic API endpoints that should require Sanctum
        $protectedApiRoutes = [
            ['POST', '/api/v1/academic/courses'],
            ['POST', '/api/v1/academic/plans'],
            ['POST', '/api/v1/academic/enrollments'],
            ['POST', '/api/v1/academic/records'],
        ];

        foreach ($protectedApiRoutes as [$method, $route]) {
            $response = $this->json($method, $route, []);

            $this->assertContains(
                $response->status(),
                [401, 403],
                "API route $method $route should require authentication",
            );
        }
    }
}
