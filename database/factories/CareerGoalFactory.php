<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentCareerGoal;

/**
 * @extends Factory<EloquentCareerGoal>
 */
final class CareerGoalFactory extends Factory
{
    protected $model = EloquentCareerGoal::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'career_profile_id' => null,
            'title' => fake()->randomElement([
                'الحصول على شهادة مهنية في إدارة المشاريع',
                'إتقان لغة برمجة جديدة',
                'بناء محفظة أعمال قوية',
                'التقديم على برنامج تدريب صيفي',
                'تطوير مهارات القيادة',
                'نشر بحث علمي',
            ]),
            'target_date' => fake()->dateTimeBetween('+1 month', '+2 years'),
            'status' => fake()->randomElement(['in_progress', 'achieved', 'abandoned']),
            'progress' => fake()->numberBetween(0, 100),
        ];
    }

    public function withCareerProfileId(string $id): static
    {
        return $this->state(fn (array $attrs) => ['career_profile_id' => $id]);
    }

    public function achieved(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => 'achieved',
            'progress' => 100,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attrs) => [
            'status' => 'in_progress',
            'progress' => fake()->numberBetween(10, 90),
        ]);
    }
}
