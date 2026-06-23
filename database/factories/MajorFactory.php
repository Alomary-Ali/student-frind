<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\Modules\Shared\Infrastructure\Persistence\EloquentMajor>
 */
final class MajorFactory extends Factory
{
    protected $model = \Modules\Shared\Infrastructure\Persistence\EloquentMajor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'department_id' => null,
            'name' => fake('ar_SA')->sentence(2),
            'name_en' => fake()->sentence(2),
            'code' => fake()->unique()->regexify('[A-Z]{6}'),
            'is_active' => true,
        ];
    }

    /** Active major state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => true]);
    }

    /** Inactive major state */
    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }

    /** Specific department_id state */
    public function withDepartmentId(string $departmentId): static
    {
        return $this->state(fn (array $attrs) => ['department_id' => $departmentId]);
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
