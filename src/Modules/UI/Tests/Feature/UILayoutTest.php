<?php

declare(strict_types=1);

namespace Modules\UI\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class UILayoutTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function dashboard_layout_has_fixed_navbar(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $response = $this->actingAs($user)->get(route('productivity.tasks'));

        $response->assertSee('navbar');
        $response->assertSee('navbar-spacer');
        $response->assertDontSee('skip-to-content-link');
    }

    #[Test]
    public function dashboard_layout_has_navbar(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $response = $this->actingAs($user)->get(route('home'));

        $response->assertSee('رفيق الطالب');
    }

    #[Test]
    public function login_page_uses_rf_components(): void
    {
        $response = $this->get(route('login'));

        $response->assertSee('رفيق الطالب');
        $response->assertSee('رقم الطالب الأكاديمي');
        $response->assertSee('كلمة المرور');
    }

    #[Test]
    public function components_can_render(): void
    {
        $this->assertTrue(class_exists(\Modules\UI\Domain\DesignTokens::class));
        $this->assertTrue(class_exists(\Modules\UI\UIServiceProvider::class));
    }
}
