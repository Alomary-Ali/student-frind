<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentLearningPath;

/**
 * @extends Factory<EloquentLearningPath>
 */
final class LearningPathFactory extends Factory
{
    protected $model = EloquentLearningPath::class;

    public function definition(): array
    {
        $stepCount = fake()->numberBetween(3, 8);

        return [
            'id' => (string) Str::uuid(),
            'student_id' => null,
            'title' => fake()->randomElement([
                'مطور ويب كامل', 'عالم بيانات', 'مهندس ذكاء اصطناعي',
                'مختبر اختراق', 'مدير مشاريع تقنية', 'مطور تطبيقات جوال',
            ]),
            'target_role' => fake()->randomElement([
                'Full-Stack Developer', 'Data Scientist', 'AI Engineer',
                'Cybersecurity Analyst', 'DevOps Engineer', 'Mobile Developer',
            ]),
            'steps' => collect(range(1, $stepCount))->map(fn (int $i) => [
                'order' => $i,
                'title' => "المرحلة $i: " . fake()->words(3, true),
                'completed' => $i === 1 ? fake()->boolean(80) : fake()->boolean(20),
            ])->toArray(),
            'progress' => fake()->numberBetween(0, 100),
            'estimated_completion_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
        ];
    }

    public function withStudentId(string $studentId): static
    {
        return $this->state(fn (array $attrs) => ['student_id' => $studentId]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attrs) => [
            'progress' => 100,
            'steps' => collect($attrs['steps'])->map(fn (array $step) => array_merge($step, ['completed' => true]))->toArray(),
        ]);
    }
}
