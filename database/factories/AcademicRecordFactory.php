<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicRecord;

/**
 * @extends Factory<EloquentAcademicRecord>
 */
final class AcademicRecordFactory extends Factory
{
    protected $model = EloquentAcademicRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gradeLetters = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'F'];
        $gradePoints = [
            'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7, 'D+' => 1.3, 'D' => 1.0, 'F' => 0.0,
        ];

        $gradeLetter = fake()->randomElement($gradeLetters);

        return [
            'id' => (string) Str::uuid(),
            'enrollment_id' => null,
            'student_id' => null,
            'course_id' => null,
            'grade_letter' => $gradeLetter,
            'grade_points' => $gradePoints[$gradeLetter],
            'recorded_at' => fake()->dateTime(),
            'recorded_by_user_id' => null,
        ];
    }

    /** Specific grade letter state */
    public function withGradeLetter(string $gradeLetter): static
    {
        $gradePoints = [
            'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7, 'D+' => 1.3, 'D' => 1.0, 'F' => 0.0,
        ];

        return $this->state(fn (array $attrs) => [
            'grade_letter' => $gradeLetter,
            'grade_points' => $gradePoints[$gradeLetter],
        ]);
    }

    /** Specific enrollment_id state */
    public function withEnrollmentId(string $enrollmentId): static
    {
        return $this->state(fn (array $attrs) => ['enrollment_id' => $enrollmentId]);
    }

    /** Specific student_id state */
    public function withStudentId(string $studentId): static
    {
        return $this->state(fn (array $attrs) => ['student_id' => $studentId]);
    }

    /** Specific course_id state */
    public function withCourseId(string $courseId): static
    {
        return $this->state(fn (array $attrs) => ['course_id' => $courseId]);
    }

    /** Specific recorded_by_user_id state */
    public function withRecordedByUserId(string $userId): static
    {
        return $this->state(fn (array $attrs) => ['recorded_by_user_id' => $userId]);
    }
}
