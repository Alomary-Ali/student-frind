<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Academic\Domain\Enums\AcademicStanding;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;

/**
 * @extends Factory<EloquentStudent>
 */
final class StudentFactory extends Factory
{
    protected $model = EloquentStudent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => null,
            'student_number' => fake()->unique()->numerify('########'),
            'academic_status' => EnrollmentStatus::Active->value,
            'academic_standing' => AcademicStanding::GoodStanding->value,
            'cumulative_gpa' => fake()->randomFloat(2, 0, 4),
            'semester_gpa' => fake()->randomFloat(2, 0, 4),
            'current_semester_id' => null,
            'institution_id' => null,
            'university_id' => null,
            'college_id' => null,
            'department_id' => null,
            'major_id' => null,
            'level' => (string) fake()->numberBetween(1, 5),
        ];
    }

    /** GoodStanding academic standing state */
    public function goodStanding(): static
    {
        return $this->state(fn (array $attrs) => [
            'academic_standing' => AcademicStanding::GoodStanding->value,
        ]);
    }

    /** Probation academic standing state */
    public function probation(): static
    {
        return $this->state(fn (array $attrs) => [
            'academic_standing' => AcademicStanding::Probation->value,
        ]);
    }

    /** Active enrollment status state */
    public function active(): static
    {
        return $this->state(fn (array $attrs) => [
            'academic_status' => EnrollmentStatus::Active->value,
        ]);
    }

    /** Graduated enrollment status state */
    public function graduated(): static
    {
        return $this->state(fn (array $attrs) => [
            'academic_status' => EnrollmentStatus::Graduated->value,
        ]);
    }

    /** Specific level state */
    public function withLevel(int $level): static
    {
        return $this->state(fn (array $attrs) => ['level' => (string) $level]);
    }

    /** Specific GPA state */
    public function withGpa(float $gpa): static
    {
        return $this->state(fn (array $attrs) => ['cumulative_gpa' => $gpa]);
    }

    /** Specific user_id state */
    public function withUserId(string $userId): static
    {
        return $this->state(fn (array $attrs) => ['user_id' => $userId]);
    }

    /** Specific university_id state */
    public function withUniversityId(string $universityId): static
    {
        return $this->state(fn (array $attrs) => ['university_id' => $universityId]);
    }

    /** Specific college_id state */
    public function withCollegeId(string $collegeId): static
    {
        return $this->state(fn (array $attrs) => ['college_id' => $collegeId]);
    }

    /** Specific department_id state */
    public function withDepartmentId(string $departmentId): static
    {
        return $this->state(fn (array $attrs) => ['department_id' => $departmentId]);
    }

    /** Specific major_id state */
    public function withMajorId(string $majorId): static
    {
        return $this->state(fn (array $attrs) => ['major_id' => $majorId]);
    }
}
