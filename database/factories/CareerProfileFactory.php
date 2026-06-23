<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentCareerProfile;

/**
 * @extends Factory<EloquentCareerProfile>
 */
final class CareerProfileFactory extends Factory
{
    protected $model = EloquentCareerProfile::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'student_id' => null,
            'major' => fake('ar_SA')->jobTitle(),
            'summary' => fake('ar_SA')->paragraph(),
            'interests' => fake()->randomElements(['تطوير الويب', 'ذكاء اصطناعي', 'علم البيانات', 'أمن سيبراني', 'هندسة برمجيات', 'تصميم واجهات'], fake()->numberBetween(1, 4)),
            'languages' => [['language' => 'العربية', 'level' => 'native'], ['language' => 'الإنجليزية', 'level' => 'advanced']],
        ];
    }

    public function withStudentId(string $studentId): static
    {
        return $this->state(fn (array $attrs) => ['student_id' => $studentId]);
    }
}
