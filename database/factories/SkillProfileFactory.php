<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentSkillProfile;

/**
 * @extends Factory<EloquentSkillProfile>
 */
final class SkillProfileFactory extends Factory
{
    protected $model = EloquentSkillProfile::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'student_id' => null,
        ];
    }

    public function withStudentId(string $studentId): static
    {
        return $this->state(fn (array $attrs) => ['student_id' => $studentId]);
    }
}
