<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 10000000;

        return [
            'id' => (string) Str::uuid(),
            'academic_id' => (string) $counter++,
            'first_name' => fake('ar_SA')->firstName(),
            'last_name' => fake('ar_SA')->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password_hash' => Hash::make('Password@123'),
            'role' => 'student',
            'status' => 'active',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ];
    }

    /** SUPER_ADMIN role state */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attrs) => ['role' => 'super_admin']);
    }

    /** ADMIN role state */
    public function admin(): static
    {
        return $this->state(fn (array $attrs) => ['role' => 'admin']);
    }

    /** ADVISOR role state */
    public function advisor(): static
    {
        return $this->state(fn (array $attrs) => ['role' => 'advisor']);
    }

    /** FACULTY role state */
    public function faculty(): static
    {
        return $this->state(fn (array $attrs) => ['role' => 'faculty']);
    }

    /** STUDENT role state */
    public function student(): static
    {
        return $this->state(fn (array $attrs) => ['role' => 'student']);
    }

    /** Active status state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'active']);
    }

    /** Suspended status state */
    public function suspended(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'suspended']);
    }

    /** Graduated status state */
    public function graduated(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'graduated']);
    }

    /** Unverified email state */
    public function unverified(): static
    {
        return $this->state(fn (array $attrs) => ['email_verified_at' => null]);
    }

    /** Specific academic_id state */
    public function withAcademicId(string $academicId): static
    {
        return $this->state(fn (array $attrs) => ['academic_id' => $academicId]);
    }

    /** Specific email state */
    public function withEmail(string $email): static
    {
        return $this->state(fn (array $attrs) => ['email' => $email]);
    }

    /** Specific password state */
    public function withPassword(string $password): static
    {
        return $this->state(fn (array $attrs) => ['password_hash' => Hash::make($password)]);
    }

    /** Locked account state */
    public function locked(): static
    {
        return $this->state(fn (array $attrs) => [
            'failed_login_attempts' => 5,
            'locked_until' => now()->addMinutes(15),
        ]);
    }
}
