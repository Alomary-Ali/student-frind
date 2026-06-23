<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentResume;

/**
 * @extends Factory<EloquentResume>
 */
final class ResumeFactory extends Factory
{
    protected $model = EloquentResume::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'career_profile_id' => null,
            'template' => fake()->randomElement(['modern', 'classic', 'minimal', 'creative']),
            'content' => json_encode([
                'sections' => ['summary', 'experience', 'education', 'skills'],
                'data' => fake()->paragraph(),
            ]),
            'generated_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function withCareerProfileId(string $id): static
    {
        return $this->state(fn (array $attrs) => ['career_profile_id' => $id]);
    }
}
