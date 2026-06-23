<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Feature\Infrastructure\Repositories;

use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Academic\Domain\Contracts\AcademicAlertRepositoryInterface;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\AcademicRecordRepositoryInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Contracts\EnrollmentRepositoryInterface;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\SemesterRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\Entities\AcademicPlan;
use Modules\Academic\Domain\Entities\AcademicRecord;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Entities\Curriculum;
use Modules\Academic\Domain\Entities\Enrollment;
use Modules\Academic\Domain\Entities\GraduationPath;
use Modules\Academic\Domain\Entities\Semester;
use Modules\Academic\Domain\Entities\SemesterPlan;
use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Domain\Enums\GradeLetter;
use Modules\Academic\Domain\ValueObjects\AcademicPlanId;
use Modules\Academic\Domain\ValueObjects\AcademicRecordId;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\EnrollmentId;
use Modules\Academic\Domain\ValueObjects\Gpa;
use Modules\Academic\Domain\ValueObjects\Grade;
use Modules\Academic\Domain\ValueObjects\GraduationPathId;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Domain\ValueObjects\SemesterPlanId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Repositories\AcademicPlanReader;
use Modules\Academic\Infrastructure\Repositories\EloquentAcademicAlertRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentAcademicPlanRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentAcademicRecordRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentCourseRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentCurriculumRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentEnrollmentRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentGraduationPathRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentSemesterPlanRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentSemesterRepository;
use Modules\Academic\Infrastructure\Repositories\EloquentStudentRepository;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class EloquentRepositoriesTest extends TestCase
{
    use RefreshDatabase;

    private StudentRepositoryInterface $studentRepository;
    private CourseRepositoryInterface $courseRepository;
    private SemesterRepositoryInterface $semesterRepository;
    private EnrollmentRepositoryInterface $enrollmentRepository;
    private CurriculumRepositoryInterface $curriculumRepository;
    private AcademicPlanRepositoryInterface $academicPlanRepository;
    private AcademicRecordRepositoryInterface $academicRecordRepository;
    private GraduationPathRepositoryInterface $graduationPathRepository;
    private AcademicAlertRepositoryInterface $academicAlertRepository;
    private SemesterPlanRepositoryInterface $semesterPlanRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->studentRepository = new EloquentStudentRepository;
        $this->courseRepository = new EloquentCourseRepository;
        $this->semesterRepository = new EloquentSemesterRepository;
        $this->enrollmentRepository = new EloquentEnrollmentRepository;
        $this->curriculumRepository = new EloquentCurriculumRepository;
        $this->academicPlanRepository = new EloquentAcademicPlanRepository;
        $this->academicRecordRepository = new EloquentAcademicRecordRepository;
        $this->graduationPathRepository = new EloquentGraduationPathRepository;
        $this->academicAlertRepository = new EloquentAcademicAlertRepository;
        $this->semesterPlanRepository = new EloquentSemesterPlanRepository;
    }

    private function createUser(string $id, string $role = 'student', ?string $email = null): EloquentUser
    {
        return EloquentUser::create([
            'id' => $id,
            'email' => $email ?? "{$id}@test.com",
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_hash' => Hash::make('password'),
            'role' => $role,
            'status' => 'active',
            'academic_id' => null,
        ]);
    }

    private function assertStudentMatches(Student $expected, Student $actual): void
    {
        $this->assertTrue($expected->id()->equals($actual->id()));
        $this->assertSame($expected->userId(), $actual->userId());
        $this->assertSame($expected->studentNumber(), $actual->studentNumber());
        $this->assertSame($expected->academicStatus(), $actual->academicStatus());
        $this->assertSame($expected->academicStanding(), $actual->academicStanding());
        $this->assertSame($expected->cumulativeGpa()->value(), $actual->cumulativeGpa()->value());
    }

    public function test_student_repository_find_by_id_returns_entity(): void
    {
        $userId = 'student-find-id-user';
        $this->createUser($userId);
        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'STU-FIND-001',
        );
        $this->studentRepository->save($student);

        $found = $this->studentRepository->findById($student->id());

        $this->assertNotNull($found);
        $this->assertStudentMatches($student, $found);
    }

    public function test_student_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->studentRepository->findById(StudentId::generate());

        $this->assertNull($found);
    }

    public function test_student_repository_find_by_user_id_returns_entity(): void
    {
        $userId = 'student-findbyuser-user';
        $this->createUser($userId);
        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'STU-FUB-001',
        );
        $this->studentRepository->save($student);

        $found = $this->studentRepository->findByUserId($userId);

        $this->assertNotNull($found);
        $this->assertSame($student->studentNumber(), $found->studentNumber());
    }

    public function test_student_repository_find_by_user_id_returns_null_when_not_found(): void
    {
        $found = $this->studentRepository->findByUserId('non-existent-user');

        $this->assertNull($found);
    }

    public function test_student_repository_exists_by_user_id_returns_true(): void
    {
        $userId = 'student-exists-user';
        $this->createUser($userId);
        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'STU-EXIST-001',
        );
        $this->studentRepository->save($student);

        $this->assertTrue($this->studentRepository->existsByUserId($userId));
    }

    public function test_student_repository_exists_by_user_id_returns_false(): void
    {
        $this->assertFalse($this->studentRepository->existsByUserId('non-existent-user'));
    }

    public function test_student_repository_save_persists_new_entity(): void
    {
        $userId = 'student-save-new-user';
        $this->createUser($userId);
        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'STU-SAVE-001',
        );

        $this->studentRepository->save($student);

        $this->assertDatabaseHas('academic_students', [
            'id' => $student->id()->value(),
            'user_id' => $userId,
            'student_number' => 'STU-SAVE-001',
        ]);
    }

    public function test_student_repository_save_updates_existing_entity(): void
    {
        $userId = 'student-update-user';
        $this->createUser($userId);
        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'STU-UPDATE-001',
        );
        $this->studentRepository->save($student);

        $student->updateGpa(Gpa::of(3.5));
        $this->studentRepository->save($student);

        $found = $this->studentRepository->findById($student->id());

        $this->assertNotNull($found);
        $this->assertSame(3.5, $found->cumulativeGpa()->value());
    }

    public function test_student_repository_save_persists_enrollments(): void
    {
        $userId = 'student-enroll-user';
        $this->createUser($userId);
        $course = Course::create(
            id: CourseId::generate(),
            code: 'CS101',
            title: 'Intro to CS',
            description: 'Basic concepts',
            creditHours: Credits::of(3),
        );
        $this->courseRepository->save($course);
        $semester = Semester::create(
            id: SemesterId::generate(),
            name: 'Fall 2026',
            code: 'F2026',
            startDate: new DateTimeImmutable('2026-09-01'),
            endDate: new DateTimeImmutable('2026-12-15'),
        );
        $this->semesterRepository->save($semester);

        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'STU-ENROLL-001',
        );
        $enrollment = Enrollment::create(
            id: EnrollmentId::generate(),
            studentId: $student->id(),
            userId: $userId,
            courseId: $course->id(),
            semesterId: $semester->id(),
        );

        $studentWithEnrollments = Student::reconstitute(
            id: $student->id(),
            userId: $student->userId(),
            studentNumber: $student->studentNumber(),
            academicStatus: $student->academicStatus(),
            academicStanding: $student->academicStanding(),
            cumulativeGpa: $student->cumulativeGpa(),
            semesterGpa: $student->semesterGpa(),
            currentSemesterId: $student->currentSemesterId(),
            institutionId: $student->institutionId(),
            universityId: $student->universityId(),
            collegeId: $student->collegeId(),
            departmentId: $student->departmentId(),
            majorId: $student->majorId(),
            level: $student->level(),
            createdAt: $student->createdAt(),
            enrollments: [$enrollment],
        );

        $this->studentRepository->save($studentWithEnrollments);

        $found = $this->studentRepository->findById($student->id());

        $this->assertNotNull($found);
        $this->assertCount(1, $found->enrollments());
    }

    public function test_course_repository_find_by_id_returns_entity(): void
    {
        $course = Course::create(
            id: CourseId::generate(),
            code: 'CS200',
            title: 'Data Structures',
            description: 'Advanced data structures',
            creditHours: Credits::of(3),
        );
        $this->courseRepository->save($course);

        $found = $this->courseRepository->findById($course->id());

        $this->assertNotNull($found);
        $this->assertSame($course->code(), $found->code());
        $this->assertSame($course->title(), $found->title());
    }

    public function test_course_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->courseRepository->findById(CourseId::generate());

        $this->assertNull($found);
    }

    public function test_course_repository_find_by_code_returns_entity(): void
    {
        $course = Course::create(
            id: CourseId::generate(),
            code: 'CS300',
            title: 'Algorithms',
            description: 'Design and analysis',
            creditHours: Credits::of(4),
        );
        $this->courseRepository->save($course);

        $found = $this->courseRepository->findByCode('CS300');

        $this->assertNotNull($found);
        $this->assertSame($course->title(), $found->title());
    }

    public function test_course_repository_find_by_code_returns_null_when_not_found(): void
    {
        $found = $this->courseRepository->findByCode('NONEXISTENT');

        $this->assertNull($found);
    }

    public function test_course_repository_save_persists_new_entity(): void
    {
        $course = Course::create(
            id: CourseId::generate(),
            code: 'CS400',
            title: 'Operating Systems',
            description: 'OS concepts',
            creditHours: Credits::of(3),
        );

        $this->courseRepository->save($course);

        $this->assertDatabaseHas('academic_courses', [
            'id' => $course->id()->value(),
            'code' => 'CS400',
            'title' => 'Operating Systems',
        ]);
    }

    public function test_course_repository_save_updates_existing_entity(): void
    {
        $course = Course::create(
            id: CourseId::generate(),
            code: 'CS401',
            title: 'Networks',
            description: 'Networking concepts',
            creditHours: Credits::of(3),
        );
        $this->courseRepository->save($course);

        $updated = Course::reconstitute(
            id: $course->id(),
            code: $course->code(),
            title: 'Advanced Networks',
            description: $course->description(),
            creditHours: $course->creditHours(),
            isActive: $course->isActive(),
            institutionId: $course->institutionId(),
            createdAt: $course->createdAt(),
        );
        $this->courseRepository->save($updated);

        $found = $this->courseRepository->findById($course->id());

        $this->assertNotNull($found);
        $this->assertSame('Advanced Networks', $found->title());
    }

    public function test_course_repository_find_all_active(): void
    {
        $course1 = Course::create(
            id: CourseId::generate(),
            code: 'CS500',
            title: 'AI',
            description: 'Artificial Intelligence',
            creditHours: Credits::of(3),
        );
        $course2 = Course::create(
            id: CourseId::generate(),
            code: 'CS501',
            title: 'ML',
            description: 'Machine Learning',
            creditHours: Credits::of(3),
        );
        $this->courseRepository->save($course1);
        $this->courseRepository->save($course2);

        $active = $this->courseRepository->findAllActive();

        $this->assertCount(2, $active);
    }

    public function test_course_repository_find_all_active_paginated(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $course = Course::create(
                id: CourseId::generate(),
                code: "CS6{$i}0",
                title: "Course {$i}",
                description: "Description {$i}",
                creditHours: Credits::of(3),
            );
            $this->courseRepository->save($course);
        }

        $paginated = $this->courseRepository->findAllActivePaginated(1, 3);

        $this->assertCount(3, $paginated->items());
        $this->assertSame(5, $paginated->total());
    }

    public function test_semester_repository_find_by_id_returns_entity(): void
    {
        $semester = Semester::create(
            id: SemesterId::generate(),
            name: 'Spring 2026',
            code: 'S2026',
            startDate: new DateTimeImmutable('2026-01-15'),
            endDate: new DateTimeImmutable('2026-05-15'),
        );
        $this->semesterRepository->save($semester);

        $found = $this->semesterRepository->findById($semester->id());

        $this->assertNotNull($found);
        $this->assertSame($semester->name(), $found->name());
    }

    public function test_semester_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->semesterRepository->findById(SemesterId::generate());

        $this->assertNull($found);
    }

    public function test_semester_repository_save_persists_new_entity(): void
    {
        $semester = Semester::create(
            id: SemesterId::generate(),
            name: 'Fall 2025',
            code: 'F2025',
            startDate: new DateTimeImmutable('2025-09-01'),
            endDate: new DateTimeImmutable('2025-12-15'),
        );

        $this->semesterRepository->save($semester);

        $this->assertDatabaseHas('academic_semesters', [
            'id' => $semester->id()->value(),
            'code' => 'F2025',
        ]);
    }

    public function test_semester_repository_save_updates_existing_entity(): void
    {
        $semester = Semester::create(
            id: SemesterId::generate(),
            name: 'Summer 2026',
            code: 'SUM2026',
            startDate: new DateTimeImmutable('2026-06-01'),
            endDate: new DateTimeImmutable('2026-08-15'),
        );
        $this->semesterRepository->save($semester);

        $updated = Semester::reconstitute(
            id: $semester->id(),
            name: 'Summer 2026 Extended',
            code: $semester->code(),
            startDate: $semester->startDate(),
            endDate: $semester->endDate(),
            isActive: false,
            institutionId: $semester->institutionId(),
            createdAt: $semester->createdAt(),
        );
        $this->semesterRepository->save($updated);

        $found = $this->semesterRepository->findById($semester->id());

        $this->assertNotNull($found);
        $this->assertFalse($found->isActive());
    }

    public function test_semester_repository_find_all_active(): void
    {
        $s1 = Semester::create(
            id: SemesterId::generate(),
            name: 'S1',
            code: 'S1',
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-06-01'),
        );
        $s2 = Semester::create(
            id: SemesterId::generate(),
            name: 'S2',
            code: 'S2',
            startDate: new DateTimeImmutable('2026-06-01'),
            endDate: new DateTimeImmutable('2026-12-01'),
        );
        $this->semesterRepository->save($s1);
        $this->semesterRepository->save($s2);

        $active = $this->semesterRepository->findAllActive();

        $this->assertCount(2, $active);
    }

    public function test_enrollment_repository_find_by_id_returns_entity(): void
    {
        $userId = 'enroll-find-user';
        $this->createUser($userId);

        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'ENR-FIND-001',
        );
        $this->studentRepository->save($student);

        $course = Course::create(
            id: CourseId::generate(),
            code: 'ENR101',
            title: 'Enrollment Test',
            description: 'Test course',
            creditHours: Credits::of(3),
        );
        $this->courseRepository->save($course);

        $semester = Semester::create(
            id: SemesterId::generate(),
            name: 'Enroll Test',
            code: 'ENRTEST',
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-05-01'),
        );
        $this->semesterRepository->save($semester);

        $enrollment = Enrollment::create(
            id: EnrollmentId::generate(),
            studentId: $student->id(),
            userId: $userId,
            courseId: $course->id(),
            semesterId: $semester->id(),
        );
        $this->enrollmentRepository->save($enrollment);

        $found = $this->enrollmentRepository->findById($enrollment->id());

        $this->assertNotNull($found);
        $this->assertTrue($found->courseId()->equals($course->id()));
    }

    public function test_enrollment_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->enrollmentRepository->findById(EnrollmentId::generate());

        $this->assertNull($found);
    }

    public function test_enrollment_repository_save_persists_new_entity(): void
    {
        $userId = 'enroll-save-user';
        $this->createUser($userId);

        $student = Student::create(
            id: StudentId::generate(),
            userId: $userId,
            studentNumber: 'ENR-SAVE-001',
        );
        $this->studentRepository->save($student);
        $course = Course::create(
            id: CourseId::generate(),
            code: 'ENR102',
            title: 'Save Test',
            description: 'Test',
            creditHours: Credits::of(3),
        );
        $this->courseRepository->save($course);
        $semester = Semester::create(
            id: SemesterId::generate(),
            name: 'Save Sem',
            code: 'SAVESEM',
            startDate: new DateTimeImmutable('2026-01-01'),
            endDate: new DateTimeImmutable('2026-05-01'),
        );
        $this->semesterRepository->save($semester);

        $enrollment = Enrollment::create(
            id: EnrollmentId::generate(),
            studentId: $student->id(),
            userId: $userId,
            courseId: $course->id(),
            semesterId: $semester->id(),
        );

        $this->enrollmentRepository->save($enrollment);

        $this->assertDatabaseHas('academic_enrollments', [
            'id' => $enrollment->id()->value(),
            'student_id' => $student->id()->value(),
            'course_id' => $course->id()->value(),
        ]);
    }

    public function test_enrollment_repository_save_updates_existing_entity(): void
    {
        $userId = 'enroll-upd-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ENR-UPD-001');
        $this->studentRepository->save($student);
        $course = Course::create(id: CourseId::generate(), code: 'ENR201', title: 'Update Test', description: 'Test', creditHours: Credits::of(3));
        $this->courseRepository->save($course);
        $semester = Semester::create(id: SemesterId::generate(), name: 'Upd Sem', code: 'UPDSEM', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $enrollment = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course->id(), semesterId: $semester->id());
        $this->enrollmentRepository->save($enrollment);

        $completed = Enrollment::reconstitute(
            id: $enrollment->id(),
            studentId: $enrollment->studentId(),
            courseId: $enrollment->courseId(),
            semesterId: $enrollment->semesterId(),
            status: EnrollmentStatus::Completed,
            enrolledAt: $enrollment->enrolledAt(),
        );
        $this->enrollmentRepository->save($completed);

        $found = $this->enrollmentRepository->findById($enrollment->id());

        $this->assertNotNull($found);
        $this->assertTrue($found->isCompleted());
    }

    public function test_enrollment_repository_exists_for_student_course_semester(): void
    {
        $userId = 'enroll-exists-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ENR-EXIST-001');
        $this->studentRepository->save($student);
        $course = Course::create(id: CourseId::generate(), code: 'ENR301', title: 'Exists Test', description: 'Test', creditHours: Credits::of(3));
        $this->courseRepository->save($course);
        $semester = Semester::create(id: SemesterId::generate(), name: 'Exists Sem', code: 'EXISTSEM', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $enrollment = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course->id(), semesterId: $semester->id());
        $this->enrollmentRepository->save($enrollment);

        $exists = $this->enrollmentRepository->existsForStudentCourseSemester($student->id(), $course->id(), $semester->id());

        $this->assertTrue($exists);
    }

    public function test_enrollment_repository_exists_returns_false_when_no_matching_enrollment(): void
    {
        $userId = 'enroll-noexists-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ENR-NEX-001');
        $this->studentRepository->save($student);
        $course = Course::create(id: CourseId::generate(), code: 'ENR302', title: 'No Exists', description: 'Test', creditHours: Credits::of(3));
        $this->courseRepository->save($course);
        $semester = Semester::create(id: SemesterId::generate(), name: 'NE Sem', code: 'NESEM', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $exists = $this->enrollmentRepository->existsForStudentCourseSemester($student->id(), $course->id(), $semester->id());

        $this->assertFalse($exists);
    }

    public function test_enrollment_repository_find_by_student_id(): void
    {
        $userId = 'enroll-fbs-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ENR-FBS-001');
        $this->studentRepository->save($student);
        $course1 = Course::create(id: CourseId::generate(), code: 'ENR401', title: 'C1', description: 'T1', creditHours: Credits::of(3));
        $course2 = Course::create(id: CourseId::generate(), code: 'ENR402', title: 'C2', description: 'T2', creditHours: Credits::of(3));
        $this->courseRepository->save($course1);
        $this->courseRepository->save($course2);
        $semester = Semester::create(id: SemesterId::generate(), name: 'FBS Sem', code: 'FBSSEM', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $e1 = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course1->id(), semesterId: $semester->id());
        $e2 = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course2->id(), semesterId: $semester->id());
        $this->enrollmentRepository->save($e1);
        $this->enrollmentRepository->save($e2);

        $enrollments = $this->enrollmentRepository->findByStudentId($student->id());

        $this->assertCount(2, $enrollments);
    }

    public function test_enrollment_repository_find_completed_by_student(): void
    {
        $userId = 'enroll-completed-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ENR-COMP-001');
        $this->studentRepository->save($student);
        $course = Course::create(id: CourseId::generate(), code: 'ENR501', title: 'Completed', description: 'Test', creditHours: Credits::of(3));
        $this->courseRepository->save($course);
        $semester = Semester::create(id: SemesterId::generate(), name: 'Comp Sem', code: 'COMPSEM', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $completed = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course->id(), semesterId: $semester->id());
        $completed->complete();
        $this->enrollmentRepository->save($completed);

        $enrollments = $this->enrollmentRepository->findCompletedByStudent($student->id());

        $this->assertCount(1, $enrollments);
    }

    public function test_curriculum_repository_find_by_id_returns_entity(): void
    {
        $curriculum = Curriculum::create(
            id: CurriculumId::generate(),
            name: 'Computer Science',
            code: 'CS-CURR',
            description: 'CS curriculum',
            totalCreditsRequired: Credits::of(12),
        );
        $this->curriculumRepository->save($curriculum);

        $found = $this->curriculumRepository->findById($curriculum->id());

        $this->assertNotNull($found);
        $this->assertSame($curriculum->name(), $found->name());
    }

    public function test_curriculum_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->curriculumRepository->findById(CurriculumId::generate());

        $this->assertNull($found);
    }

    public function test_curriculum_repository_save_persists_new_entity(): void
    {
        $curriculum = Curriculum::create(
            id: CurriculumId::generate(),
            name: 'Mathematics',
            code: 'MATH-CURR',
            description: 'Math curriculum',
            totalCreditsRequired: Credits::of(12),
        );

        $this->curriculumRepository->save($curriculum);

        $this->assertDatabaseHas('academic_curricula', [
            'id' => $curriculum->id()->value(),
            'code' => 'MATH-CURR',
        ]);
    }

    public function test_curriculum_repository_save_updates_existing_entity(): void
    {
        $curriculum = Curriculum::create(
            id: CurriculumId::generate(),
            name: 'Physics',
            code: 'PHY-CURR',
            description: 'Physics curriculum',
            totalCreditsRequired: Credits::of(12),
        );
        $this->curriculumRepository->save($curriculum);

        $updated = Curriculum::reconstitute(
            id: $curriculum->id(),
            name: 'Physics Updated',
            code: $curriculum->code(),
            description: $curriculum->description(),
            totalCreditsRequired: $curriculum->totalCreditsRequired(),
            institutionId: $curriculum->institutionId(),
            createdAt: $curriculum->createdAt(),
        );
        $this->curriculumRepository->save($updated);

        $found = $this->curriculumRepository->findById($curriculum->id());

        $this->assertNotNull($found);
        $this->assertSame('Physics Updated', $found->name());
    }

    public function test_curriculum_repository_saves_courses(): void
    {
        $course = Course::create(
            id: CourseId::generate(),
            code: 'CURR101',
            title: 'Curriculum Course',
            description: 'Test',
            creditHours: Credits::of(3),
        );
        $this->courseRepository->save($course);

        $curriculum = Curriculum::create(
            id: CurriculumId::generate(),
            name: 'Test Curriculum',
            code: 'TEST-CURR',
            description: 'Test',
            totalCreditsRequired: Credits::of(12),
        );
        $curriculum->addCourse($course->id(), true, 1);
        $this->curriculumRepository->save($curriculum);

        $found = $this->curriculumRepository->findById($curriculum->id());

        $this->assertNotNull($found);
        $this->assertCount(1, $found->courses());
    }

    public function test_academic_plan_repository_find_by_id_returns_entity(): void
    {
        $userId = 'plan-find-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'AP-FIND-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Plan Curr', code: 'PLANC', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $plan = AcademicPlan::assign(
            id: AcademicPlanId::generate(),
            studentId: $student->id(),
            curriculumId: $curriculum->id(),
        );
        $this->academicPlanRepository->save($plan);

        $found = $this->academicPlanRepository->findById($plan->id());

        $this->assertNotNull($found);
        $this->assertTrue($found->studentId()->equals($student->id()));
    }

    public function test_academic_plan_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->academicPlanRepository->findById(AcademicPlanId::generate());

        $this->assertNull($found);
    }

    public function test_academic_plan_repository_save_persists_new_entity(): void
    {
        $userId = 'plan-save-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'AP-SAVE-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Save Curr', code: 'SAVEC', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $plan = AcademicPlan::assign(
            id: AcademicPlanId::generate(),
            studentId: $student->id(),
            curriculumId: $curriculum->id(),
        );
        $this->academicPlanRepository->save($plan);

        $this->assertDatabaseHas('academic_plans', [
            'id' => $plan->id()->value(),
            'student_id' => $student->id()->value(),
        ]);
    }

    public function test_academic_plan_repository_save_updates_existing_entity(): void
    {
        $userId = 'plan-upd-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'AP-UPD-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Upd Curr', code: 'UPDC', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $plan = AcademicPlan::assign(id: AcademicPlanId::generate(), studentId: $student->id(), curriculumId: $curriculum->id());
        $this->academicPlanRepository->save($plan);

        $plan->complete();
        $this->academicPlanRepository->save($plan);

        $found = $this->academicPlanRepository->findById($plan->id());

        $this->assertNotNull($found);
        $this->assertFalse($found->isActive());
    }

    public function test_academic_plan_repository_find_active_by_student_id(): void
    {
        $userId = 'plan-active-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'AP-ACT-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Active Curr', code: 'ACTC', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $plan = AcademicPlan::assign(id: AcademicPlanId::generate(), studentId: $student->id(), curriculumId: $curriculum->id());
        $this->academicPlanRepository->save($plan);

        $found = $this->academicPlanRepository->findActiveByStudentId($student->id());

        $this->assertNotNull($found);
        $this->assertTrue($found->isActive());
    }

    public function test_academic_plan_repository_find_active_by_student_id_returns_null_when_no_active_plan(): void
    {
        $userId = 'plan-noact-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'AP-NOACT-001');
        $this->studentRepository->save($student);

        $found = $this->academicPlanRepository->findActiveByStudentId($student->id());

        $this->assertNull($found);
    }

    public function test_academic_record_repository_save_persists_new_entity(): void
    {
        $userId = 'rec-save-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'REC-SAVE-001');
        $this->studentRepository->save($student);
        $course = Course::create(id: CourseId::generate(), code: 'REC101', title: 'Record Course', description: 'Test', creditHours: Credits::of(3));
        $this->courseRepository->save($course);
        $semester = Semester::create(id: SemesterId::generate(), name: 'Rec Sem', code: 'RECSEM', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);
        $enrollment = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course->id(), semesterId: $semester->id());
        $this->enrollmentRepository->save($enrollment);

        $record = AcademicRecord::record(
            id: AcademicRecordId::generate(),
            enrollmentId: $enrollment->id(),
            studentId: $student->id()->value(),
            userId: $userId,
            courseId: $course->id()->value(),
            grade: Grade::fromLetter(GradeLetter::A),
            recordedByUserId: $userId,
        );
        $this->academicRecordRepository->save($record);

        $this->assertDatabaseHas('academic_records', [
            'enrollment_id' => $enrollment->id()->value(),
            'grade_letter' => 'A',
        ]);
    }

    public function test_academic_record_repository_find_by_enrollment_id(): void
    {
        $userId = 'rec-find-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'REC-FIND-001');
        $this->studentRepository->save($student);
        $course = Course::create(id: CourseId::generate(), code: 'REC201', title: 'Find Record', description: 'Test', creditHours: Credits::of(3));
        $this->courseRepository->save($course);
        $semester = Semester::create(id: SemesterId::generate(), name: 'Find Rec', code: 'FINDREC', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);
        $enrollment = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course->id(), semesterId: $semester->id());
        $this->enrollmentRepository->save($enrollment);

        $record = AcademicRecord::record(
            id: AcademicRecordId::generate(),
            enrollmentId: $enrollment->id(),
            studentId: $student->id()->value(),
            userId: $userId,
            courseId: $course->id()->value(),
            grade: Grade::fromLetter(GradeLetter::B),
            recordedByUserId: $userId,
        );
        $this->academicRecordRepository->save($record);

        $found = $this->academicRecordRepository->findByEnrollmentId($enrollment->id());

        $this->assertNotNull($found);
        $this->assertSame('B', $found->grade()->letterValue());
    }

    public function test_academic_record_repository_find_by_enrollment_id_returns_null_when_not_found(): void
    {
        $found = $this->academicRecordRepository->findByEnrollmentId(EnrollmentId::generate());

        $this->assertNull($found);
    }

    public function test_academic_record_repository_find_graded_records_by_student_id(): void
    {
        $userId = 'rec-graded-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'REC-GRD-001');
        $this->studentRepository->save($student);
        $course = Course::create(id: CourseId::generate(), code: 'REC301', title: 'Graded Course', description: 'Test', creditHours: Credits::of(3));
        $this->courseRepository->save($course);
        $semester = Semester::create(id: SemesterId::generate(), name: 'Graded Sem', code: 'GRDSEM', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);
        $enrollment = Enrollment::create(id: EnrollmentId::generate(), studentId: $student->id(), userId: $userId, courseId: $course->id(), semesterId: $semester->id());
        $this->enrollmentRepository->save($enrollment);

        $record = AcademicRecord::record(
            id: AcademicRecordId::generate(),
            enrollmentId: $enrollment->id(),
            studentId: $student->id()->value(),
            userId: $userId,
            courseId: $course->id()->value(),
            grade: Grade::fromLetter(GradeLetter::A),
            recordedByUserId: $userId,
        );
        $this->academicRecordRepository->save($record);

        $records = $this->academicRecordRepository->findGradedRecordsByStudentId($student->id());

        $this->assertCount(1, $records);
        $this->assertSame(4.0, $records[0]['grade_points']);
        $this->assertSame(3, $records[0]['credit_hours']);
    }

    public function test_graduation_path_repository_find_by_student_id_returns_entity(): void
    {
        $userId = 'grad-find-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'GRAD-FIND-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Grad Curr', code: 'GRADC', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $path = GraduationPath::initialize(
            id: GraduationPathId::generate(),
            studentId: $student->id(),
            curriculumId: $curriculum->id(),
            creditsRequired: Credits::of(12),
        );
        $this->graduationPathRepository->save($path);

        $found = $this->graduationPathRepository->findByStudentId($student->id());

        $this->assertNotNull($found);
        $this->assertTrue($found->studentId()->equals($student->id()));
    }

    public function test_graduation_path_repository_find_by_student_id_returns_null_when_not_found(): void
    {
        $found = $this->graduationPathRepository->findByStudentId(StudentId::generate());

        $this->assertNull($found);
    }

    public function test_graduation_path_repository_save_persists_new_entity(): void
    {
        $userId = 'grad-save-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'GRAD-SAVE-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Grad Save', code: 'GRADS', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $path = GraduationPath::initialize(
            id: GraduationPathId::generate(),
            studentId: $student->id(),
            curriculumId: $curriculum->id(),
            creditsRequired: Credits::of(12),
        );
        $this->graduationPathRepository->save($path);

        $this->assertDatabaseHas('academic_graduation_paths', [
            'student_id' => $student->id()->value(),
            'completion_percentage' => 0.0,
        ]);
    }

    public function test_graduation_path_repository_save_updates_existing_entity(): void
    {
        $userId = 'grad-upd-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'GRAD-UPD-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Grad Upd', code: 'GRADU', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $path = GraduationPath::initialize(id: GraduationPathId::generate(), studentId: $student->id(), curriculumId: $curriculum->id(), creditsRequired: Credits::of(12));
        $this->graduationPathRepository->save($path);

        $updated = GraduationPath::reconstitute(
            id: $path->id(),
            studentId: $path->studentId(),
            curriculumId: $path->curriculumId(),
            creditsEarned: Credits::of(3),
            creditsRequired: Credits::of(12),
            completionPercentage: 25.0,
            isOnTrack: true,
            estimatedGraduationDate: new DateTimeImmutable('2028-06-01'),
            updatedAt: new DateTimeImmutable,
        );
        $this->graduationPathRepository->save($updated);

        $found = $this->graduationPathRepository->findByStudentId($student->id());

        $this->assertNotNull($found);
        $this->assertSame(25.0, $found->completionPercentage());
    }

    public function test_academic_alert_repository_find_by_id_returns_entity(): void
    {
        $userId = 'alert-find-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ALERT-FIND-001');
        $this->studentRepository->save($student);

        $alert = AcademicAlert::create(
            id: AlertId::generate(),
            studentId: $student->id(),
            alertType: AlertType::LowGpa,
            severity: AlertSeverity::Medium,
            message: 'Test alert',
        );
        $this->academicAlertRepository->save($alert);

        $found = $this->academicAlertRepository->findById($alert->id());

        $this->assertNotNull($found);
        $this->assertSame('Test alert', $found->message());
    }

    public function test_academic_alert_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->academicAlertRepository->findById(AlertId::generate());

        $this->assertNull($found);
    }

    public function test_academic_alert_repository_save_persists_new_entity(): void
    {
        $userId = 'alert-save-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ALERT-SAVE-001');
        $this->studentRepository->save($student);

        $alert = AcademicAlert::create(
            id: AlertId::generate(),
            studentId: $student->id(),
            alertType: AlertType::GraduationDelay,
            severity: AlertSeverity::Critical,
            message: 'GPA drop detected',
        );
        $this->academicAlertRepository->save($alert);

        $this->assertDatabaseHas('academic_advisory_alerts', [
            'id' => $alert->id()->value(),
            'message' => 'GPA drop detected',
        ]);
    }

    public function test_academic_alert_repository_save_updates_existing_entity(): void
    {
        $userId = 'alert-upd-user';
        $resolverId = '550e8400-e29b-41d4-a716-446655449999';
        $this->createUser($userId);
        $this->createUser($resolverId, 'admin');
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ALERT-UPD-001');
        $this->studentRepository->save($student);

        $alert = AcademicAlert::create(id: AlertId::generate(), studentId: $student->id(), alertType: AlertType::LowGpa, severity: AlertSeverity::Medium, message: 'Initial');
        $this->academicAlertRepository->save($alert);

        $alert->resolve($resolverId);
        $this->academicAlertRepository->save($alert);

        $found = $this->academicAlertRepository->findById($alert->id());

        $this->assertNotNull($found);
        $this->assertTrue($found->isResolved());
    }

    public function test_academic_alert_repository_find_by_student_id(): void
    {
        $userId = 'alert-fbs-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ALERT-FBS-001');
        $this->studentRepository->save($student);

        $a1 = AcademicAlert::create(id: AlertId::generate(), studentId: $student->id(), alertType: AlertType::LowGpa, severity: AlertSeverity::Medium, message: 'Alert 1');
        $a2 = AcademicAlert::create(id: AlertId::generate(), studentId: $student->id(), alertType: AlertType::GraduationDelay, severity: AlertSeverity::Critical, message: 'Alert 2');
        $this->academicAlertRepository->save($a1);
        $this->academicAlertRepository->save($a2);

        $alerts = $this->academicAlertRepository->findByStudentId($student->id());

        $this->assertCount(2, $alerts);
    }

    public function test_academic_alert_repository_find_unresolved_by_student_id(): void
    {
        $userId = 'alert-unres-user';
        $resolverId = '550e8400-e29b-41d4-a716-446655449998';
        $this->createUser($userId);
        $this->createUser($resolverId, 'admin');
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ALERT-UNR-001');
        $this->studentRepository->save($student);

        $a1 = AcademicAlert::create(id: AlertId::generate(), studentId: $student->id(), alertType: AlertType::LowGpa, severity: AlertSeverity::Medium, message: 'Unresolved');
        $a2 = AcademicAlert::create(id: AlertId::generate(), studentId: $student->id(), alertType: AlertType::GraduationDelay, severity: AlertSeverity::Critical, message: 'Resolved');
        $a2->resolve($resolverId);
        $this->academicAlertRepository->save($a1);
        $this->academicAlertRepository->save($a2);

        $unresolved = $this->academicAlertRepository->findUnresolvedByStudentId($student->id());

        $this->assertCount(1, $unresolved);
        $this->assertSame('Unresolved', $unresolved[0]->message());
    }

    public function test_academic_alert_repository_exists_for_student_and_type(): void
    {
        $userId = 'alert-exist-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ALERT-EXT-001');
        $this->studentRepository->save($student);

        $alert = AcademicAlert::create(id: AlertId::generate(), studentId: $student->id(), alertType: AlertType::LowGpa, severity: AlertSeverity::Medium, message: 'Check');
        $this->academicAlertRepository->save($alert);

        $exists = $this->academicAlertRepository->existsForStudentAndType($student->id(), AlertType::LowGpa->value);

        $this->assertTrue($exists);
    }

    public function test_academic_alert_repository_exists_returns_false_when_no_matching_alert(): void
    {
        $userId = 'alert-noext-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'ALERT-NOE-001');
        $this->studentRepository->save($student);

        $exists = $this->academicAlertRepository->existsForStudentAndType($student->id(), AlertType::LowGpa->value);

        $this->assertFalse($exists);
    }

    public function test_semester_plan_repository_find_by_id_returns_entity(): void
    {
        $userId = 'sp-find-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'SP-FIND-001');
        $this->studentRepository->save($student);
        $semester = Semester::create(id: SemesterId::generate(), name: 'SP Find', code: 'SPF', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $plan = SemesterPlan::create(
            id: SemesterPlanId::generate(),
            studentId: $student->id(),
            semesterId: $semester->id(),
            plannedCourses: ['CS101', 'CS102'],
            totalCredits: 6,
        );
        $this->semesterPlanRepository->save($plan);

        $found = $this->semesterPlanRepository->findById($plan->id());

        $this->assertNotNull($found);
        $this->assertSame(6, $found->totalCredits());
    }

    public function test_semester_plan_repository_find_by_id_returns_null_when_not_found(): void
    {
        $found = $this->semesterPlanRepository->findById(SemesterPlanId::generate());

        $this->assertNull($found);
    }

    public function test_semester_plan_repository_save_persists_new_entity(): void
    {
        $userId = 'sp-save-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'SP-SAVE-001');
        $this->studentRepository->save($student);
        $semester = Semester::create(id: SemesterId::generate(), name: 'SP Save', code: 'SPS', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $plan = SemesterPlan::create(
            id: SemesterPlanId::generate(),
            studentId: $student->id(),
            semesterId: $semester->id(),
            plannedCourses: ['MATH101'],
            totalCredits: 3,
        );
        $this->semesterPlanRepository->save($plan);

        $this->assertDatabaseHas('academic_semester_plans', [
            'id' => $plan->id()->value(),
            'total_credits' => 3,
        ]);
    }

    public function test_semester_plan_repository_save_updates_existing_entity(): void
    {
        $userId = 'sp-upd-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'SP-UPD-001');
        $this->studentRepository->save($student);
        $semester = Semester::create(id: SemesterId::generate(), name: 'SP Upd', code: 'SPU', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $plan = SemesterPlan::create(id: SemesterPlanId::generate(), studentId: $student->id(), semesterId: $semester->id(), plannedCourses: ['PHY101'], totalCredits: 3);
        $this->semesterPlanRepository->save($plan);

        $plan->submit();
        $this->semesterPlanRepository->save($plan);

        $found = $this->semesterPlanRepository->findById($plan->id());

        $this->assertNotNull($found);
        $this->assertSame('submitted', $found->status());
    }

    public function test_semester_plan_repository_find_by_student_and_semester(): void
    {
        $userId = 'sp-sas-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'SP-SAS-001');
        $this->studentRepository->save($student);
        $semester = Semester::create(id: SemesterId::generate(), name: 'SP SAS', code: 'SASS', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $plan = SemesterPlan::create(id: SemesterPlanId::generate(), studentId: $student->id(), semesterId: $semester->id(), plannedCourses: ['CS201'], totalCredits: 3);
        $this->semesterPlanRepository->save($plan);

        $found = $this->semesterPlanRepository->findByStudentAndSemester($student->id(), $semester->id());

        $this->assertNotNull($found);
        $this->assertSame($plan->id()->value(), $found->id()->value());
    }

    public function test_semester_plan_repository_find_by_student_and_semester_returns_null_when_not_found(): void
    {
        $userId = 'sp-sasnull-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'SP-SASN-001');
        $this->studentRepository->save($student);
        $semester = Semester::create(id: SemesterId::generate(), name: 'SP Null', code: 'SPN', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $this->semesterRepository->save($semester);

        $found = $this->semesterPlanRepository->findByStudentAndSemester($student->id(), $semester->id());

        $this->assertNull($found);
    }

    public function test_semester_plan_repository_find_by_student(): void
    {
        $userId = 'sp-fbs-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'SP-FBS-001');
        $this->studentRepository->save($student);
        $s1 = Semester::create(id: SemesterId::generate(), name: 'SP1', code: 'SP1', startDate: new DateTimeImmutable('2026-01-01'), endDate: new DateTimeImmutable('2026-05-01'));
        $s2 = Semester::create(id: SemesterId::generate(), name: 'SP2', code: 'SP2', startDate: new DateTimeImmutable('2026-06-01'), endDate: new DateTimeImmutable('2026-12-01'));
        $this->semesterRepository->save($s1);
        $this->semesterRepository->save($s2);

        $p1 = SemesterPlan::create(id: SemesterPlanId::generate(), studentId: $student->id(), semesterId: $s1->id(), plannedCourses: ['CS301'], totalCredits: 3);
        $p2 = SemesterPlan::create(id: SemesterPlanId::generate(), studentId: $student->id(), semesterId: $s2->id(), plannedCourses: ['CS302'], totalCredits: 3);
        $this->semesterPlanRepository->save($p1);
        $this->semesterPlanRepository->save($p2);

        $plans = $this->semesterPlanRepository->findByStudent($student->id());

        $this->assertCount(2, $plans);
    }

    public function test_academic_plan_reader_get_student_profile(): void
    {
        $userId = 'reader-profile-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'READER-PRO-001');
        $this->studentRepository->save($student);

        $reader = new AcademicPlanReader(
            $this->studentRepository,
            $this->academicPlanRepository,
            $this->curriculumRepository,
            $this->graduationPathRepository,
        );

        $profile = $reader->getStudentProfile($student->id()->value());

        $this->assertNotNull($profile);
        $this->assertSame($student->userId(), $profile->userId);
        $this->assertSame($student->studentNumber(), $profile->studentNumber);
    }

    public function test_academic_plan_reader_get_student_profile_returns_null_when_not_found(): void
    {
        $reader = new AcademicPlanReader(
            $this->studentRepository,
            $this->academicPlanRepository,
            $this->curriculumRepository,
            $this->graduationPathRepository,
        );

        $profile = $reader->getStudentProfile('00000000-0000-0000-0000-000000000099');

        $this->assertNull($profile);
    }

    public function test_academic_plan_reader_get_active_plan(): void
    {
        $userId = 'reader-plan-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'READER-PLN-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Reader Plan', code: 'RDRPLN', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $plan = AcademicPlan::assign(id: AcademicPlanId::generate(), studentId: $student->id(), curriculumId: $curriculum->id());
        $this->academicPlanRepository->save($plan);

        $reader = new AcademicPlanReader(
            $this->studentRepository,
            $this->academicPlanRepository,
            $this->curriculumRepository,
            $this->graduationPathRepository,
        );

        $summary = $reader->getActivePlan($student->id()->value());

        $this->assertNotNull($summary);
        $this->assertSame($curriculum->name(), $summary->curriculumName);
    }

    public function test_academic_plan_reader_get_active_plan_returns_null_when_no_active_plan(): void
    {
        $userId = 'reader-noplan-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'READER-NOP-001');
        $this->studentRepository->save($student);

        $reader = new AcademicPlanReader(
            $this->studentRepository,
            $this->academicPlanRepository,
            $this->curriculumRepository,
            $this->graduationPathRepository,
        );

        $summary = $reader->getActivePlan($student->id()->value());

        $this->assertNull($summary);
    }

    public function test_academic_plan_reader_get_graduation_progress(): void
    {
        $userId = 'reader-grad-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'READER-GRD-001');
        $this->studentRepository->save($student);
        $curriculum = Curriculum::create(id: CurriculumId::generate(), name: 'Reader Grad', code: 'RDRGRD', description: 'Test', totalCreditsRequired: Credits::of(12));
        $this->curriculumRepository->save($curriculum);

        $path = GraduationPath::initialize(
            id: GraduationPathId::generate(),
            studentId: $student->id(),
            curriculumId: $curriculum->id(),
            creditsRequired: Credits::of(12),
        );
        $this->graduationPathRepository->save($path);

        $reader = new AcademicPlanReader(
            $this->studentRepository,
            $this->academicPlanRepository,
            $this->curriculumRepository,
            $this->graduationPathRepository,
        );

        $progress = $reader->getGraduationProgress($student->id()->value());

        $this->assertNotNull($progress);
        $this->assertSame(0, $progress->creditsEarned);
        $this->assertSame(12, $progress->creditsRequired);
        $this->assertSame(0.0, $progress->completionPercentage);
    }

    public function test_academic_plan_reader_get_graduation_progress_returns_null_when_no_student(): void
    {
        $reader = new AcademicPlanReader(
            $this->studentRepository,
            $this->academicPlanRepository,
            $this->curriculumRepository,
            $this->graduationPathRepository,
        );

        $progress = $reader->getGraduationProgress('00000000-0000-0000-0000-000000000099');

        $this->assertNull($progress);
    }

    public function test_academic_plan_reader_get_graduation_progress_returns_null_when_no_path(): void
    {
        $userId = 'reader-nopath-user';
        $this->createUser($userId);
        $student = Student::create(id: StudentId::generate(), userId: $userId, studentNumber: 'READER-NOPATH-001');
        $this->studentRepository->save($student);

        $reader = new AcademicPlanReader(
            $this->studentRepository,
            $this->academicPlanRepository,
            $this->curriculumRepository,
            $this->graduationPathRepository,
        );

        $progress = $reader->getGraduationProgress($student->id()->value());

        $this->assertNull($progress);
    }
}
