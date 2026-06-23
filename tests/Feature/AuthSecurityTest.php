<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Security-critical tests: Authentication, Authorization, Rate Limiting.
 * These MUST pass before any deployment.
 */
final class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ── Login ──────────────────────────────────────────────────────────────

    public function test_login_page_is_accessible_to_guests(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_student_can_login_with_valid_credentials(): void
    {
        User::factory()->withAcademicId('12345678')->create();

        $response = $this->post('/login', [
            'academic_id' => '12345678',
            'password'    => 'Password@123',
        ]);

        $response->assertRedirect(route('academic.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->withAcademicId('12345678')->create();

        $response = $this->post('/login', [
            'academic_id' => '12345678',
            'password'    => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('academic_id');
        $this->assertGuest();
    }

    public function test_login_fails_with_nonexistent_academic_id(): void
    {
        $response = $this->post('/login', [
            'academic_id' => '99999999',
            'password'    => 'password',
        ]);

        $response->assertSessionHasErrors('academic_id');
        $this->assertGuest();
    }

    public function test_login_fails_with_invalid_academic_id_format(): void
    {
        // Academic ID must be exactly 8 digits
        $response = $this->post('/login', [
            'academic_id' => 'abc',
            'password'    => 'password',
        ]);

        $response->assertSessionHasErrors('academic_id');
    }

    public function test_suspended_user_cannot_login(): void
    {
        User::factory()->withAcademicId('12345678')->suspended()->create();

        $response = $this->post('/login', [
            'academic_id' => '12345678',
            'password'    => 'Password@123',
        ]);

        $response->assertSessionHasErrors('academic_id');
        $this->assertGuest();
    }

    // ── Logout ─────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_logout_requires_post_method(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // GET logout should not work (405 Method Not Allowed or redirect)
        $this->get('/logout')->assertStatus(405);
    }

    // ── Guest Middleware ───────────────────────────────────────────────────

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get('/login')
             ->assertRedirect();
    }

    // ── Rate Limiting ──────────────────────────────────────────────────────

    public function test_login_is_rate_limited_after_5_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'academic_id' => '00000000',
                'password'    => 'wrong',
            ]);
        }

        $response = $this->post('/login', [
            'academic_id' => '00000000',
            'password'    => 'wrong',
        ]);

        $response->assertStatus(429);
    }
}
