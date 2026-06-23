<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\Application\UseCases\CreateStudent;
use Modules\Academic\Application\UseCases\EnrollStudentInCourse;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Infrastructure\Persistence\EloquentCourse;
use Modules\Academic\Infrastructure\Persistence\EloquentSemester;
use Modules\Academic\Infrastructure\Repositories\EloquentCourseRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentEnrollmentRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentSemesterRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentStudentRepository;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class AcademicModuleIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private StudentRepositoryInterface $studentRepository;
    private CourseRepositoryInterface $courseRepository;
    private SemesterRepositoryInterface $semesterRepository;
    private EnrollmentRepositoryInterface $enrollmentRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studentRepository = new EloquentStudentRepository;
        $this->courseRepository = new EloquentCourseRepository;
        $this->semesterRepository = new EloquentSemesterRepository;
        $this->enrollmentRepository = new EloquentEnrollmentRepository;
    }

    public function test_student_creation_and_enrollment_flow(): void
    {
        // Step 1: Create a user
        $userId = (string) \Illuminate\Support\Str::uuid();
        $user = EloquentUser::create([
            'id' => $userId,
            'email' => 'integration@test.com',
            'first_name' => 'Integration',
            'last_name' => 'Test',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => '12345678',
        ]);

        // Step 2: Create a student using the use case
        $userRepository = new \Modules\Shared\Infrastructure\Repositories\EloquentUserRepository;

        $createStudent = new CreateStudent(
            $this->studentRepository,
            $userRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface::class),
            new \Modules\Academic\Application\Mappers\AcademicMapper,
        );

        $studentDto = new \Modules\Academic\Application\DTOs\CreateStudentDto(
            userId: $userId,
            studentNumber: 'INT-001',
            universityId: 'university-uuid',
            collegeId: 'college-uuid',
            departmentId: 'department-uuid',
            majorId: 'major-uuid',
            level: '1',
        );

        $student = $createStudent->execute($studentDto);

        $this->assertNotNull($student);
        $this->assertEquals('INT-001', $student->studentNumber);

        // Step 3: Create a course
        $course = EloquentCourse::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'code' => 'CS101',
            'title' => 'Introduction to Computer Science',
            'description' => 'Basic CS concepts',
            'credit_hours' => 3,
            'is_active' => true,
        ]);

        // Step 4: Create a semester
        $semester = EloquentSemester::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'Fall 2026',
            'name_en' => 'Fall 2026',
            'code' => 'FALL2026',
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-15',
            'is_active' => true,
        ]);

        // Step 5: Enroll the student in the course
        $enrollStudent = new EnrollStudentInCourse(
            $this->studentRepository,
            $this->courseRepository,
            $this->semesterRepository,
            $this->enrollmentRepository,
            new \Modules\Academic\Infrastructure\Integrations\LaravelTransactionManager,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface::class),
            new \Modules\Academic\Application\Mappers\AcademicMapper,
            new \Modules\Academic\Domain\Services\PrerequisiteValidationService($this->courseRepository),
        );

        $enrollDto = new \Modules\Academic\Application\DTOs\EnrollStudentDto(
            studentId: $student->id,
            courseId: $course->id,
            semesterId: $semester->id,
            actorUserId: $user->id,
        );

        $enrollment = $enrollStudent->execute($enrollDto);

        $this->assertNotNull($enrollment);
        $this->assertEquals($student->id, $enrollment->studentId);
    }

    public function test_course_caching_integration(): void
    {
        // Create a course
        $course = EloquentCourse::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'code' => 'CS102',
            'title' => 'Data Structures',
            'description' => 'Advanced CS concepts',
            'credit_hours' => 3,
            'is_active' => true,
        ]);

        // First call - should hit database
        $firstCall = $this->courseRepository->findById(
            \Modules\Academic\Domain\ValueObjects\CourseId::fromString($course->id),
        );

        $this->assertNotNull($firstCall);
        $this->assertEquals('CS102', $firstCall->code());

        // Second call - should hit cache
        $secondCall = $this->courseRepository->findById(
            \Modules\Academic\Domain\ValueObjects\CourseId::fromString($course->id),
        );

        $this->assertNotNull($secondCall);
        $this->assertEquals('CS102', $secondCall->code());
    }
}
