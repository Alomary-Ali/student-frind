<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Feature\Infrastructure\Persistence;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicAlert;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicAuditLog;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicPlan;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicRecord;
use Modules\Academic\Infrastructure\Persistence\EloquentCourse;
use Modules\Academic\Infrastructure\Persistence\EloquentCurriculum;
use Modules\Academic\Infrastructure\Persistence\EloquentCurriculumCourse;
use Modules\Academic\Infrastructure\Persistence\EloquentEnrollment;
use Modules\Academic\Infrastructure\Persistence\EloquentGraduationPath;
use Modules\Academic\Infrastructure\Persistence\EloquentSemester;
use Modules\Academic\Infrastructure\Persistence\EloquentSemesterPlan;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class EloquentModelsTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $role = 'student'): EloquentUser
    {
        return EloquentUser::create([
            'id' => (string) Str::uuid(),
            'email' => Str::random(6) . '@test.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_hash' => Hash::make('password'),
            'role' => $role,
            'status' => 'active',
        ]);
    }

    private function createStudent(?EloquentUser $user = null): EloquentStudent
    {
        $user ??= $this->createUser();

        return EloquentStudent::create([
            'user_id' => $user->id,
            'student_number' => 'STU-' . Str::random(6),
            'academic_status' => 'active',
            'academic_standing' => 'good',
            'cumulative_gpa' => 3.5,
        ]);
    }

    private function createCourse(): EloquentCourse
    {
        return EloquentCourse::create([
            'code' => 'CS' . Str::random(4),
            'title' => 'Test Course',
            'description' => 'A test course description',
            'credit_hours' => 3,
            'is_active' => true,
        ]);
    }

    private function createSemester(): EloquentSemester
    {
        return EloquentSemester::create([
            'name' => 'Fall 2026',
            'name_en' => 'Fall 2026',
            'code' => 'F2026-' . Str::random(4),
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
        ]);
    }

    private function createCurriculum(): EloquentCurriculum
    {
        return EloquentCurriculum::create([
            'id' => (string) Str::uuid(),
            'name' => 'Computer Science',
            'code' => 'CS-CURR-' . Str::random(4),
            'description' => 'A test curriculum',
            'total_credits_required' => 120,
        ]);
    }

    // ──────────────────────────────────────────────
    // EloquentStudent
    // ──────────────────────────────────────────────

    public function test_student_create_and_find(): void
    {
        $user = $this->createUser();
        $student = EloquentStudent::create([
            'user_id' => $user->id,
            'student_number' => 'STU-2026-001',
            'academic_status' => 'active',
            'academic_standing' => 'good',
            'cumulative_gpa' => 3.75,
        ]);

        $this->assertNotNull($student->id);
        $this->assertEquals($user->id, $student->user_id);
        $this->assertEquals('STU-2026-001', $student->student_number);
        $this->assertEquals(3.75, $student->cumulative_gpa);

        $found = EloquentStudent::find($student->id);
        $this->assertNotNull($found);
        $this->assertEquals($student->id, $found->id);
    }

    public function test_student_update(): void
    {
        $student = $this->createStudent();
        $student->update(['cumulative_gpa' => 4.0, 'academic_standing' => 'excellent']);

        $fresh = $student->fresh();
        $this->assertEquals(4.0, $fresh->cumulative_gpa);
        $this->assertEquals('excellent', $fresh->academic_standing);
    }

    public function test_student_delete(): void
    {
        $student = $this->createStudent();
        $id = $student->id;

        $student->delete();

        $this->assertNull(EloquentStudent::find($id));
        $this->assertDatabaseMissing('academic_students', ['id' => $id]);
    }

    public function test_student_fillable_guarded(): void
    {
        $student = $this->createStudent();
        $student->update(['non_existent_field' => 'value']);

        $this->assertNull($student->getAttribute('non_existent_field'));
    }

    public function test_student_cast_cumulative_gpa(): void
    {
        $student = $this->createStudent();
        $student->update(['cumulative_gpa' => '3.50']);

        $this->assertIsFloat($student->fresh()->cumulative_gpa);
        $this->assertEquals(3.5, $student->fresh()->cumulative_gpa);
    }

    // ──────────────────────────────────────────────
    // EloquentCourse
    // ──────────────────────────────────────────────

    public function test_course_create_and_find(): void
    {
        $course = EloquentCourse::create([
            'code' => 'CS101',
            'title' => 'Introduction to Programming',
            'description' => 'Learn programming fundamentals',
            'credit_hours' => 3,
            'is_active' => true,
        ]);

        $this->assertNotNull($course->id);
        $this->assertEquals('CS101', $course->code);
        $this->assertTrue($course->is_active);

        $found = EloquentCourse::find($course->id);
        $this->assertNotNull($found);
    }

    public function test_course_update(): void
    {
        $course = $this->createCourse();
        $course->update(['title' => 'Updated Title', 'credit_hours' => 4]);

        $fresh = $course->fresh();
        $this->assertEquals('Updated Title', $fresh->title);
        $this->assertEquals(4, $fresh->credit_hours);
    }

    public function test_course_delete(): void
    {
        $course = $this->createCourse();
        $id = $course->id;

        $course->delete();

        $this->assertNull(EloquentCourse::find($id));
    }

    public function test_course_casts(): void
    {
        $course = $this->createCourse();

        $this->assertIsBool($course->is_active);
        $this->assertIsInt($course->credit_hours);
    }

    // ──────────────────────────────────────────────
    // EloquentSemester
    // ──────────────────────────────────────────────

    public function test_semester_create_and_find(): void
    {
        $semester = EloquentSemester::create([
            'name' => 'Spring 2026',
            'name_en' => 'Spring 2026',
            'code' => 'SP2026',
            'start_date' => '2026-01-15',
            'end_date' => '2026-05-30',
            'is_active' => true,
        ]);

        $this->assertNotNull($semester->id);
        $this->assertEquals('Spring 2026', $semester->name);
        $this->assertTrue($semester->is_active);

        $found = EloquentSemester::find($semester->id);
        $this->assertNotNull($found);
    }

    public function test_semester_update(): void
    {
        $semester = $this->createSemester();
        $semester->update(['is_active' => false]);

        $this->assertFalse($semester->fresh()->is_active);
    }

    public function test_semester_delete(): void
    {
        $semester = $this->createSemester();
        $id = $semester->id;

        $semester->delete();

        $this->assertNull(EloquentSemester::find($id));
    }

    public function test_semester_date_casts(): void
    {
        $semester = $this->createSemester();

        $this->assertInstanceOf(\DateTimeInterface::class, $semester->start_date);
        $this->assertInstanceOf(\DateTimeInterface::class, $semester->end_date);
        $this->assertIsBool($semester->is_active);
    }

    // ──────────────────────────────────────────────
    // EloquentCurriculum
    // ──────────────────────────────────────────────

    public function test_curriculum_create_and_find(): void
    {
        $curriculum = EloquentCurriculum::create([
            'id' => (string) Str::uuid(),
            'name' => 'Software Engineering',
            'code' => 'SE-CURR',
            'description' => 'A software engineering curriculum',
            'total_credits_required' => 130,
        ]);

        $this->assertEquals('Software Engineering', $curriculum->name);
        $this->assertEquals(130, $curriculum->total_credits_required);

        $found = EloquentCurriculum::find($curriculum->id);
        $this->assertNotNull($found);
    }

    public function test_curriculum_update(): void
    {
        $curriculum = $this->createCurriculum();
        $curriculum->update(['total_credits_required' => 125]);

        $this->assertEquals(125, $curriculum->fresh()->total_credits_required);
    }

    public function test_curriculum_delete(): void
    {
        $curriculum = $this->createCurriculum();
        $id = $curriculum->id;

        $curriculum->delete();

        $this->assertNull(EloquentCurriculum::find($id));
    }

    public function test_curriculum_relationship_courses(): void
    {
        $curriculum = $this->createCurriculum();
        $course = $this->createCourse();

        $curriculumCourse = EloquentCurriculumCourse::create([
            'id' => (string) Str::uuid(),
            'curriculum_id' => $curriculum->id,
            'course_id' => $course->id,
            'is_required' => true,
            'semester_order' => 1,
        ]);

        $this->assertCount(1, $curriculum->courses);
        $this->assertEquals($course->id, $curriculum->courses->first()->course_id);
    }

    // ──────────────────────────────────────────────
    // EloquentCurriculumCourse
    // ──────────────────────────────────────────────

    public function test_curriculum_course_create_and_find(): void
    {
        $curriculum = $this->createCurriculum();
        $course = $this->createCourse();

        $cc = EloquentCurriculumCourse::create([
            'id' => (string) Str::uuid(),
            'curriculum_id' => $curriculum->id,
            'course_id' => $course->id,
            'is_required' => true,
            'semester_order' => 1,
        ]);

        $this->assertNotNull($cc->id);
        $this->assertTrue($cc->is_required);
        $this->assertEquals(1, $cc->semester_order);

        $found = EloquentCurriculumCourse::find($cc->id);
        $this->assertNotNull($found);
    }

    public function test_curriculum_course_update(): void
    {
        $curriculum = $this->createCurriculum();
        $course = $this->createCourse();

        $cc = EloquentCurriculumCourse::create([
            'id' => (string) Str::uuid(),
            'curriculum_id' => $curriculum->id,
            'course_id' => $course->id,
            'is_required' => true,
            'semester_order' => 1,
        ]);

        $cc->update(['is_required' => false, 'semester_order' => 2]);

        $fresh = $cc->fresh();
        $this->assertFalse($fresh->is_required);
        $this->assertEquals(2, $fresh->semester_order);
    }

    public function test_curriculum_course_delete(): void
    {
        $curriculum = $this->createCurriculum();
        $course = $this->createCourse();

        $cc = EloquentCurriculumCourse::create([
            'id' => (string) Str::uuid(),
            'curriculum_id' => $curriculum->id,
            'course_id' => $course->id,
            'is_required' => true,
            'semester_order' => 1,
        ]);
        $id = $cc->id;

        $cc->delete();

        $this->assertNull(EloquentCurriculumCourse::find($id));
    }

    public function test_curriculum_course_belongs_to_curriculum(): void
    {
        $curriculum = $this->createCurriculum();
        $course = $this->createCourse();

        $cc = EloquentCurriculumCourse::create([
            'id' => (string) Str::uuid(),
            'curriculum_id' => $curriculum->id,
            'course_id' => $course->id,
            'is_required' => true,
            'semester_order' => 1,
        ]);

        $this->assertNotNull($cc->curriculum);
        $this->assertEquals($curriculum->id, $cc->curriculum->id);
    }

    // ──────────────────────────────────────────────
    // EloquentAcademicPlan
    // ──────────────────────────────────────────────

    public function test_academic_plan_create_and_find(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $plan = EloquentAcademicPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        $this->assertNotNull($plan->id);
        $this->assertEquals($student->id, $plan->student_id);
        $this->assertEquals('active', $plan->status);

        $found = EloquentAcademicPlan::find($plan->id);
        $this->assertNotNull($found);
    }

    public function test_academic_plan_update(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $plan = EloquentAcademicPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        $plan->update(['status' => 'completed']);

        $this->assertEquals('completed', $plan->fresh()->status);
    }

    public function test_academic_plan_delete(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $plan = EloquentAcademicPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'status' => 'active',
            'assigned_at' => now(),
        ]);
        $id = $plan->id;

        $plan->delete();

        $this->assertNull(EloquentAcademicPlan::find($id));
    }

    public function test_academic_plan_datetime_cast(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $plan = EloquentAcademicPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'status' => 'active',
            'assigned_at' => '2026-01-15 10:00:00',
        ]);

        $this->assertInstanceOf(\DateTimeInterface::class, $plan->assigned_at);
    }

    // ──────────────────────────────────────────────
    // EloquentEnrollment
    // ──────────────────────────────────────────────

    public function test_enrollment_create_and_find(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $this->assertNotNull($enrollment->id);
        $this->assertEquals($student->id, $enrollment->student_id);

        $found = EloquentEnrollment::find($enrollment->id);
        $this->assertNotNull($found);
    }

    public function test_enrollment_update(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $enrollment->update(['status' => 'completed']);

        $this->assertEquals('completed', $enrollment->fresh()->status);
    }

    public function test_enrollment_delete(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);
        $id = $enrollment->id;

        $enrollment->delete();

        $this->assertNull(EloquentEnrollment::find($id));
    }

    public function test_enrollment_relationships(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $this->assertNotNull($enrollment->course);
        $this->assertEquals($course->id, $enrollment->course->id);

        $this->assertNotNull($enrollment->semester);
        $this->assertEquals($semester->id, $enrollment->semester->id);

        $this->assertNotNull($enrollment->student);
        $this->assertEquals($student->id, $enrollment->student->id);
    }

    // ──────────────────────────────────────────────
    // EloquentAcademicRecord
    // ──────────────────────────────────────────────

    public function test_academic_record_create_and_find(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();
        $admin = $this->createUser('admin');

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $record = EloquentAcademicRecord::create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $student->id,
            'course_id' => $course->id,
            'grade_letter' => 'A',
            'grade_points' => 4.0,
            'recorded_at' => now(),
            'recorded_by_user_id' => $admin->id,
        ]);

        $this->assertNotNull($record->id);
        $this->assertEquals('A', $record->grade_letter);

        $found = EloquentAcademicRecord::find($record->id);
        $this->assertNotNull($found);
    }

    public function test_academic_record_update(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();
        $admin = $this->createUser('admin');

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $record = EloquentAcademicRecord::create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $student->id,
            'course_id' => $course->id,
            'grade_letter' => 'B',
            'grade_points' => 3.0,
            'recorded_at' => now(),
            'recorded_by_user_id' => $admin->id,
        ]);

        $record->update(['grade_letter' => 'A', 'grade_points' => 4.0]);

        $fresh = $record->fresh();
        $this->assertEquals('A', $fresh->grade_letter);
        $this->assertEquals(4.0, $fresh->grade_points);
    }

    public function test_academic_record_delete(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();
        $admin = $this->createUser('admin');

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $record = EloquentAcademicRecord::create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $student->id,
            'course_id' => $course->id,
            'grade_letter' => 'A',
            'grade_points' => 4.0,
            'recorded_at' => now(),
            'recorded_by_user_id' => $admin->id,
        ]);
        $id = $record->id;

        $record->delete();

        $this->assertNull(EloquentAcademicRecord::find($id));
    }

    public function test_academic_record_casts(): void
    {
        $student = $this->createStudent();
        $course = $this->createCourse();
        $semester = $this->createSemester();
        $admin = $this->createUser('admin');

        $enrollment = EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $record = EloquentAcademicRecord::create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $student->id,
            'course_id' => $course->id,
            'grade_letter' => 'A',
            'grade_points' => '4.0',
            'recorded_at' => '2026-01-15 10:00:00',
            'recorded_by_user_id' => $admin->id,
        ]);

        $this->assertIsFloat($record->grade_points);
        $this->assertInstanceOf(\DateTimeInterface::class, $record->recorded_at);
    }

    // ──────────────────────────────────────────────
    // EloquentGraduationPath
    // ──────────────────────────────────────────────

    public function test_graduation_path_create_and_find(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $path = EloquentGraduationPath::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'credits_earned' => 60,
            'credits_required' => 120,
            'completion_percentage' => 50.0,
            'is_on_track' => true,
            'estimated_graduation_date' => '2028-06-15',
        ]);

        $this->assertNotNull($path->id);
        $this->assertEquals(50.0, $path->completion_percentage);
        $this->assertTrue($path->is_on_track);

        $found = EloquentGraduationPath::find($path->id);
        $this->assertNotNull($found);
    }

    public function test_graduation_path_update(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $path = EloquentGraduationPath::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'credits_earned' => 60,
            'credits_required' => 120,
            'completion_percentage' => 50.0,
            'is_on_track' => true,
        ]);

        $path->update(['credits_earned' => 90, 'completion_percentage' => 75.0]);

        $fresh = $path->fresh();
        $this->assertEquals(90, $fresh->credits_earned);
        $this->assertEquals(75.0, $fresh->completion_percentage);
    }

    public function test_graduation_path_delete(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $path = EloquentGraduationPath::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'credits_earned' => 60,
            'credits_required' => 120,
            'completion_percentage' => 50.0,
            'is_on_track' => true,
        ]);
        $id = $path->id;

        $path->delete();

        $this->assertNull(EloquentGraduationPath::find($id));
    }

    public function test_graduation_path_casts(): void
    {
        $student = $this->createStudent();
        $curriculum = $this->createCurriculum();

        $path = EloquentGraduationPath::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'curriculum_id' => $curriculum->id,
            'credits_earned' => 60,
            'credits_required' => 120,
            'completion_percentage' => '50.0',
            'is_on_track' => 1,
            'estimated_graduation_date' => '2028-06-15',
        ]);

        $this->assertIsInt($path->credits_earned);
        $this->assertIsInt($path->credits_required);
        $this->assertIsFloat($path->completion_percentage);
        $this->assertIsBool($path->is_on_track);
        $this->assertInstanceOf(\DateTimeInterface::class, $path->estimated_graduation_date);
    }

    // ──────────────────────────────────────────────
    // EloquentAcademicAlert
    // ──────────────────────────────────────────────

    public function test_academic_alert_create_and_find(): void
    {
        $student = $this->createStudent();

        $alert = EloquentAcademicAlert::create([
            'student_id' => $student->id,
            'alert_type' => 'low_gpa',
            'severity' => 'high',
            'message' => 'Student GPA below 2.0',
            'metadata' => ['gpa' => 1.5, 'threshold' => 2.0],
            'is_resolved' => false,
        ]);

        $this->assertNotNull($alert->id);
        $this->assertEquals('low_gpa', $alert->alert_type);
        $this->assertEquals('high', $alert->severity);

        $found = EloquentAcademicAlert::find($alert->id);
        $this->assertNotNull($found);
    }

    public function test_academic_alert_update(): void
    {
        $student = $this->createStudent();

        $alert = EloquentAcademicAlert::create([
            'student_id' => $student->id,
            'alert_type' => 'low_gpa',
            'severity' => 'medium',
            'message' => 'GPA warning',
            'is_resolved' => false,
        ]);

        $alert->update(['is_resolved' => true, 'severity' => 'low']);

        $fresh = $alert->fresh();
        $this->assertTrue($fresh->is_resolved);
        $this->assertEquals('low', $fresh->severity);
    }

    public function test_academic_alert_delete(): void
    {
        $student = $this->createStudent();

        $alert = EloquentAcademicAlert::create([
            'student_id' => $student->id,
            'alert_type' => 'low_gpa',
            'severity' => 'high',
            'message' => 'Test alert',
            'is_resolved' => false,
        ]);
        $id = $alert->id;

        $alert->delete();

        $this->assertNull(EloquentAcademicAlert::find($id));
    }

    public function test_academic_alert_casts(): void
    {
        $student = $this->createStudent();

        $alert = EloquentAcademicAlert::create([
            'student_id' => $student->id,
            'alert_type' => 'low_gpa',
            'severity' => 'high',
            'message' => 'Test',
            'metadata' => ['key' => 'value'],
            'is_resolved' => 0,
        ]);

        $this->assertIsArray($alert->metadata);
        $this->assertEquals('value', $alert->metadata['key']);
        $this->assertIsBool($alert->is_resolved);
        $this->assertFalse($alert->is_resolved);
    }

    public function test_academic_alert_belongs_to_student(): void
    {
        $student = $this->createStudent();

        $alert = EloquentAcademicAlert::create([
            'student_id' => $student->id,
            'alert_type' => 'low_gpa',
            'severity' => 'high',
            'message' => 'Test alert',
            'is_resolved' => false,
        ]);

        $this->assertNotNull($alert->student);
        $this->assertEquals($student->id, $alert->student->id);
    }

    // ──────────────────────────────────────────────
    // EloquentSemesterPlan
    // ──────────────────────────────────────────────

    public function test_semester_plan_create_and_find(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();

        $plan = EloquentSemesterPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'semester_id' => $semester->id,
            'planned_courses' => ['course-uuid-1', 'course-uuid-2'],
            'total_credits' => 15,
            'status' => 'draft',
        ]);

        $this->assertNotNull($plan->id);
        $this->assertEquals('draft', $plan->status);
        $this->assertEquals(15, $plan->total_credits);

        $found = EloquentSemesterPlan::find($plan->id);
        $this->assertNotNull($found);
    }

    public function test_semester_plan_update(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();

        $plan = EloquentSemesterPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'semester_id' => $semester->id,
            'planned_courses' => [],
            'total_credits' => 12,
            'status' => 'draft',
        ]);

        $plan->update(['status' => 'submitted', 'submitted_at' => now()]);

        $fresh = $plan->fresh();
        $this->assertEquals('submitted', $fresh->status);
        $this->assertNotNull($fresh->submitted_at);
    }

    public function test_semester_plan_delete(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();

        $plan = EloquentSemesterPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'semester_id' => $semester->id,
            'planned_courses' => [],
            'total_credits' => 12,
            'status' => 'draft',
        ]);
        $id = $plan->id;

        $plan->delete();

        $this->assertNull(EloquentSemesterPlan::find($id));
    }

    public function test_semester_plan_casts(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();

        $plan = EloquentSemesterPlan::create([
            'id' => (string) Str::uuid(),
            'student_id' => $student->id,
            'semester_id' => $semester->id,
            'planned_courses' => ['id-1', 'id-2'],
            'total_credits' => '15',
            'status' => 'draft',
        ]);

        $this->assertIsArray($plan->planned_courses);
        $this->assertIsInt($plan->total_credits);
        $this->assertEquals(15, $plan->total_credits);
    }

    // ──────────────────────────────────────────────
    // EloquentAcademicAuditLog
    // ──────────────────────────────────────────────

    public function test_audit_log_create_and_find(): void
    {
        $user = $this->createUser();

        $log = EloquentAcademicAuditLog::create([
            'id' => (string) Str::uuid(),
            'actor_user_id' => $user->id,
            'action' => 'create_student',
            'entity_type' => 'student',
            'entity_id' => (string) Str::uuid(),
            'old_values' => null,
            'new_values' => ['name' => 'Test'],
        ]);

        $this->assertNotNull($log->id);
        $this->assertEquals('create_student', $log->action);

        $found = EloquentAcademicAuditLog::find($log->id);
        $this->assertNotNull($found);
    }

    public function test_audit_log_update(): void
    {
        $user = $this->createUser();

        $log = EloquentAcademicAuditLog::create([
            'id' => (string) Str::uuid(),
            'actor_user_id' => $user->id,
            'action' => 'create_student',
            'entity_type' => 'student',
            'entity_id' => (string) Str::uuid(),
            'new_values' => ['name' => 'Test'],
        ]);

        $log->update(['action' => 'update_student']);

        $this->assertEquals('update_student', $log->fresh()->action);
    }

    public function test_audit_log_delete(): void
    {
        $user = $this->createUser();

        $log = EloquentAcademicAuditLog::create([
            'id' => (string) Str::uuid(),
            'actor_user_id' => $user->id,
            'action' => 'delete_student',
            'entity_type' => 'student',
            'entity_id' => (string) Str::uuid(),
        ]);
        $id = $log->id;

        $log->delete();

        $this->assertNull(EloquentAcademicAuditLog::find($id));
    }

    public function test_audit_log_json_casts(): void
    {
        $user = $this->createUser();
        $entityId = (string) Str::uuid();

        $log = EloquentAcademicAuditLog::create([
            'id' => (string) Str::uuid(),
            'actor_user_id' => $user->id,
            'action' => 'update_student',
            'entity_type' => 'student',
            'entity_id' => $entityId,
            'old_values' => ['name' => 'Old', 'gpa' => 2.0],
            'new_values' => ['name' => 'New', 'gpa' => 3.5],
        ]);

        $this->assertIsArray($log->old_values);
        $this->assertIsArray($log->new_values);
        $this->assertEquals('Old', $log->old_values['name']);
        $this->assertEquals('New', $log->new_values['name']);
    }

    // ──────────────────────────────────────────────
    // Student -> Enrollments Relationship
    // ──────────────────────────────────────────────

    public function test_student_has_many_enrollments(): void
    {
        $student = $this->createStudent();
        $course1 = $this->createCourse();
        $course2 = $this->createCourse();
        $semester = $this->createSemester();

        EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course1->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        EloquentEnrollment::create([
            'student_id' => $student->id,
            'course_id' => $course2->id,
            'semester_id' => $semester->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $this->assertCount(2, $student->enrollments);
    }
}
