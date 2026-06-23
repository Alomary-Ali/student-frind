<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Academic\Infrastructure\Persistence\EloquentCourse;

/**
 * @extends Factory<EloquentCourse>
 */
final class CourseFactory extends Factory
{
    protected $model = EloquentCourse::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'code' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'title' => fake('ar_SA')->sentence(3),
            'description' => fake('ar_SA')->paragraph(),
            'credit_hours' => fake()->numberBetween(1, 4),
            'is_active' => true,
            'institution_id' => null,
        ];
    }

    /** Active course state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => true]);
    }

    /** Inactive course state */
    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['is_active' => false]);
    }

    /** Specific code state */
    public function withCode(string $code): static
    {
        return $this->state(fn (array $attrs) => ['code' => $code]);
    }

    /** Specific credit hours state */
    public function withCreditHours(int $hours): static
    {
        return $this->state(fn (array $attrs) => ['credit_hours' => $hours]);
    }

    /** Specific institution_id state */
    public function withInstitutionId(string $institutionId): static
    {
        return $this->state(fn (array $attrs) => ['institution_id' => $institutionId]);
    }
}
