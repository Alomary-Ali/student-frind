<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentSkill;

/**
 * @extends Factory<EloquentSkill>
 */
final class SkillFactory extends Factory
{
    protected $model = EloquentSkill::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'skill_profile_id' => null,
            'name' => fake()->randomElement([
                'PHP', 'Laravel', 'JavaScript', 'TypeScript', 'Python', 'Java',
                'SQL', 'Git', 'Docker', 'AWS', 'Machine Learning', 'Data Analysis',
                'UI/UX Design', '项目管理', 'Communication', 'Leadership',
            ]),
            'category' => fake()->randomElement(['technical', 'soft', 'language', 'tool']),
            'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'years_of_experience' => fake()->numberBetween(0, 10),
            'last_used' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }

    public function withSkillProfileId(string $id): static
    {
        return $this->state(fn (array $attrs) => ['skill_profile_id' => $id]);
    }

    public function advanced(): static
    {
        return $this->state(fn (array $attrs) => [
            'level' => 'advanced',
            'years_of_experience' => fake()->numberBetween(3, 8),
        ]);
    }
}
