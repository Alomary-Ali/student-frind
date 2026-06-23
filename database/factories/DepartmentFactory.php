<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\Modules\Shared\Infrastructure\Persistence\EloquentDepartment>
 */
final class DepartmentFactory extends Factory
{
    protected $model = \Modules\Shared\Infrastructure\Persistence\EloquentDepartment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'college_id' => null,
            'name' => fake('ar_SA')->sentence(2),
            'name_en' => fake()->sentence(2),
            'code' => fake()->unique()->regexify('[A-Z]{5}'),
            'is_active' => true,
        ];
    }

    /** Active department state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => true]);
    }

    /** Inactive department state */
    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }

    /** Specific college_id state */
    public function withCollegeId(string $collegeId): static
    {
        return $this->state(fn (array $attrs) => ['college_id' => $collegeId]);
    }

    /** Specific code state */
    public function withCode(string $code): static
    {
        return $this->state(fn (array $attrs) => ['code' => $code]);
    }

    /** Specific name state */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attrs) => ['name' => $name]);
    }
}
