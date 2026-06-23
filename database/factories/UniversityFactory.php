<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\Modules\Shared\Infrastructure\Persistence\EloquentUniversity>
 */
final class UniversityFactory extends Factory
{
    protected $model = \Modules\Shared\Infrastructure\Persistence\EloquentUniversity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => fake('ar_SA')->company(),
            'name_en' => fake()->company(),
            'code' => fake()->unique()->regexify('[A-Z]{3}'),
            'is_active' => true,
        ];
    }

    /** Active university state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => true]);
    }

    /** Inactive university state */
    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
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
