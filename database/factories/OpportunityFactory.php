<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentOpportunity;

/**
 * @extends Factory<EloquentOpportunity>
 */
final class OpportunityFactory extends Factory
{
    protected $model = EloquentOpportunity::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => fake('ar_SA')->jobTitle(),
            'description' => fake('ar_SA')->paragraph(),
            'provider' => fake()->randomElement(['linkedin', 'coursera', 'edrak', 'manual']),
            'type' => fake()->randomElement(['job', 'internship', 'scholarship', 'course', 'competition', 'volunteering', 'conference']),
            'location' => fake('ar_SA')->city(),
            'country' => fake('ar_SA')->country(),
            'deadline' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'apply_url' => fake()->url(),
            'status' => 'active',
            'metadata' => [],
            'source_url' => null,
            'image_url' => null,
            'tags' => fake()->randomElements(['تطوير', 'تقنية', 'هندسة', 'علوم', 'أعمال', 'فنون'], 2),
        ];
    }

    public function ofType(string $type): static
    {
        return $this->state(fn (array $attrs) => ['type' => $type]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'closed']);
    }
}
