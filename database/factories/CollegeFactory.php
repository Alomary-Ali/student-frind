<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\Modules\Shared\Infrastructure\Persistence\EloquentCollege>
 */
final class CollegeFactory extends Factory
{
    protected $model = \Modules\Shared\Infrastructure\Persistence\EloquentCollege::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'university_id' => null,
            'name' => fake('ar_SA')->sentence(2),
            'name_en' => fake()->sentence(2),
            'code' => fake()->unique()->regexify('[A-Z]{4}'),
            'is_active' => true,
        ];
    }

    /** Active college state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => true]);
    }

    /** Inactive college state */
    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }

    /** Specific university_id state */
    public function withUniversityId(string $universityId): static
    {
        return $this->state(fn (array $attrs) => ['university_id' => $universityId]);
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
