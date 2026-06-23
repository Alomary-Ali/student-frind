<?php

declare(strict_types=1);

namespace Tests\Feature\AppControllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthPageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible_to_guests(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_page_redirects_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect();
    }

    public function test_register_page_is_accessible_to_guests(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_register_page_redirects_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register'));

        $response->assertRedirect();
    }

    public function test_unauthorized_page_is_accessible(): void
    {
        $response = $this->get(route('unauthorized'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.unauthorized');
    }
}
