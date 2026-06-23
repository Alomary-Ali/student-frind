<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentPortfolioItem;

/**
 * @extends Factory<EloquentPortfolioItem>
 */
final class PortfolioItemFactory extends Factory
{
    protected $model = EloquentPortfolioItem::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-3 years', '-1 month');

        return [
            'id' => (string) Str::uuid(),
            'career_profile_id' => null,
            'title' => fake()->catchPhrase(),
            'description' => fake('ar_SA')->paragraph(),
            'project_url' => fake()->boolean(70) ? fake()->url() : null,
            'github_url' => fake()->boolean(50) ? 'https://github.com/user/' . fake()->slug() : null,
            'start_date' => $start,
            'end_date' => fake()->boolean(60) ? fake()->dateTimeBetween($start->format('Y-m-d'), 'now') : null,
            'technologies' => fake()->randomElements(['Laravel', 'Vue.js', 'React', 'Python', 'TensorFlow', 'Docker', 'PostgreSQL', 'Redis'], fake()->numberBetween(1, 4)),
        ];
    }

    public function withCareerProfileId(string $id): static
    {
        return $this->state(fn (array $attrs) => ['career_profile_id' => $id]);
    }
}
