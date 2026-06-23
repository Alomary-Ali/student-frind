<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Academic\Infrastructure\Persistence\EloquentSemester;

/**
 * @extends Factory<EloquentSemester>
 */
final class SemesterFactory extends Factory
{
    protected $model = EloquentSemester::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->numberBetween(2024, 2026);
        $semester = fake()->randomElement(['First', 'Second', 'Summer']);

        return [
            'id' => (string) Str::uuid(),
            'name' => fake('ar_SA')->sentence(3),
            'code' => "{$year}-{$semester}",
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'is_active' => fake()->boolean(70),
            'institution_id' => null,
        ];
    }

    /** Active semester state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => true]);
    }

    /** Inactive semester state */
    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }

    /** Specific code state */
    public function withCode(string $code): static
    {
        return $this->state(fn (array $attrs) => ['code' => $code]);
    }

    /** Specific institution_id state */
    public function withInstitutionId(string $institutionId): static
    {
        return $this->state(fn (array $attrs) => ['institution_id' => $institutionId]);
    }
}
