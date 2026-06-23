<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentExperience;

/**
 * @extends Factory<EloquentExperience>
 */
final class ExperienceFactory extends Factory
{
    protected $model = EloquentExperience::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-5 years', '-1 month');
        $isCurrent = fake()->boolean(20);

        return [
            'id' => (string) Str::uuid(),
            'career_profile_id' => null,
            'company' => fake()->company(),
            'position' => fake()->jobTitle(),
            'description' => fake('ar_SA')->paragraph(),
            'start_date' => $start,
            'end_date' => $isCurrent ? null : fake()->dateTimeBetween($start->format('Y-m-d'), 'now'),
            'is_current' => $isCurrent,
        ];
    }

    public function withCareerProfileId(string $id): static
    {
        return $this->state(fn (array $attrs) => ['career_profile_id' => $id]);
    }

    public function current(): static
    {
        return $this->state(fn (array $attrs) => [
            'end_date' => null,
            'is_current' => true,
        ]);
    }
}
