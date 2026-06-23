<?php

declare(strict_types=1);

namespace Tests\Unit\AppModels;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_valid_user(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->id);
        $this->assertNotNull($user->academic_id);
        $this->assertNotNull($user->first_name);
        $this->assertNotNull($user->last_name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password_hash);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_fillable_attributes(): void
    {
        $user = new User;
        $fillable = $user->getFillable();

        $this->assertContains('academic_id', $fillable);
        $this->assertContains('first_name', $fillable);
        $this->assertContains('last_name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password_hash', $fillable);
        $this->assertContains('role', $fillable);
        $this->assertContains('status', $fillable);
    }

    public function test_hidden_attributes(): void
    {
        $user = new User;
        $hidden = $user->getHidden();

        $this->assertContains('password_hash', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    public function test_casts(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
        $this->assertIsString($user->role);
    }

    public function test_uses_uuids(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(\Illuminate\Support\Str::isUuid($user->id));
        $this->assertFalse($user->getIncrementing());
    }

    public function test_get_auth_password_returns_password_hash(): void
    {
        $user = User::factory()->create();

        $this->assertEquals($user->password_hash, $user->getAuthPassword());
    }

    public function test_factory_states_create_specific_roles(): void
    {
        $admin = User::factory()->admin()->create();
        $this->assertEquals('admin', $admin->role);

        $advisor = User::factory()->advisor()->create();
        $this->assertEquals('advisor', $advisor->role);

        $faculty = User::factory()->faculty()->create();
        $this->assertEquals('faculty', $faculty->role);

        $student = User::factory()->student()->create();
        $this->assertEquals('student', $student->role);
    }

    public function test_factory_states_create_specific_statuses(): void
    {
        $active = User::factory()->active()->create();
        $this->assertEquals('active', $active->status);

        $suspended = User::factory()->suspended()->create();
        $this->assertEquals('suspended', $suspended->status);

        $graduated = User::factory()->graduated()->create();
        $this->assertEquals('graduated', $graduated->status);
    }

    public function test_unverified_state_sets_email_verified_at_to_null(): void
    {
        $user = User::factory()->unverified()->create();

        $this->assertNull($user->email_verified_at);
    }

    public function test_locked_state_sets_failed_attempts_and_locked_until(): void
    {
        $user = User::factory()->locked()->create();

        $this->assertEquals(5, $user->failed_login_attempts);
        $this->assertNotNull($user->locked_until);
    }
}
