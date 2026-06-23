<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentAchievement;

/**
 * @extends Factory<EloquentAchievement>
 */
final class AchievementFactory extends Factory
{
    protected $model = EloquentAchievement::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'student_id' => null,
            'type' => fake()->randomElement(['course', 'skill', 'project', 'certification', 'milestone']),
            'title' => fake()->randomElement([
                'أول مشروع PHP', 'إتمام دورة Laravel', 'مهارة التواصل الفعال',
                'شهادة AWS', '100 يوم برمجة', 'مشروع التخرج',
            ]),
            'description' => fake('ar_SA')->sentence(),
            'badge_url' => fake()->boolean(70) ? fake()->imageUrl(120, 120, 'badge') : null,
            'unlocked_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'created_at' => fn (array $attrs) => $attrs['unlocked_at'],
        ];
    }

    public function withStudentId(string $studentId): static
    {
        return $this->state(fn (array $attrs) => ['student_id' => $studentId]);
    }

    public function ofType(string $type): static
    {
        return $this->state(fn (array $attrs) => ['type' => $type]);
    }
}
