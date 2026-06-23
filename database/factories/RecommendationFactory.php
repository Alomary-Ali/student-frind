<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentRecommendation;

/**
 * @extends Factory<EloquentRecommendation>
 */
final class RecommendationFactory extends Factory
{
    protected $model = EloquentRecommendation::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'student_id' => null,
            'opportunity_id' => null,
            'score' => fake()->randomFloat(2, 10, 95),
            'reason' => fake('ar_SA')->sentence(),
            'generated_at' => now(),
        ];
    }

    public function highScore(): static
    {
        return $this->state(fn (array $attrs) => ['score' => fake()->randomFloat(2, 70, 99)]);
    }
}
