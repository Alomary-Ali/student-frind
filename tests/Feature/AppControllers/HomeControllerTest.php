<?php

declare(strict_types=1);

namespace Tests\Feature\AppControllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_authenticated_user_can_view_home_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_home_page_has_stats_variable(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertViewHas('stats');
    }

    public function test_home_page_returns_default_stats_when_no_student_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertViewHas('stats', [
            'gpa' => 0,
            'progress' => 0,
            'skills' => 0,
            'readiness' => 75,
            'courses' => 0,
        ]);
    }
}
