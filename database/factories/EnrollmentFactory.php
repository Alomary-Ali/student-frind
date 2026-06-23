<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Infrastructure\Persistence\EloquentEnrollment;

/**
 * @extends Factory<EloquentEnrollment>
 */
final class EnrollmentFactory extends Factory
{
    protected $model = EloquentEnrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'student_id' => null,
            'course_id' => null,
            'semester_id' => null,
            'status' => EnrollmentStatus::Active->value,
            'enrolled_at' => fake()->dateTime(),
        ];
    }

    /** Active enrollment status state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => ['status' => EnrollmentStatus::Active->value]);
    }

    /** Inactive enrollment status state */
    public function inactive(): static
    {
        return $this->state(fn (array $attrs) => ['status' => EnrollmentStatus::Inactive->value]);
    }

    /** Graduated enrollment status state */
    public function graduated(): static
    {
        return $this->state(fn (array $attrs) => ['status' => EnrollmentStatus::Graduated->value]);
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

    /** Specific semester_id state */
    public function withSemesterId(string $semesterId): static
    {
        return $this->state(fn (array $attrs) => ['semester_id' => $semesterId]);
    }
}
