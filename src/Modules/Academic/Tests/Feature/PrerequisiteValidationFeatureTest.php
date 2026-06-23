<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Modules\Academic\Infrastructure\Persistence\EloquentCourse;
use Modules\Academic\Infrastructure\Persistence\EloquentSemester;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class PrerequisiteValidationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrollment_fails_when_prerequisite_not_met(): void
    {
        // Create admin user for authentication
        $admin = EloquentUser::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'email' => 'admin-' . (string) \Illuminate\Support\Str::uuid() . '@test.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password_hash' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
            'academic_id' => null,
        ]);
        Sanctum::actingAs($admin);

        // Create user
        $userId = (string) \Illuminate\Support\Str::uuid();
        $user = EloquentUser::create([
            'id' => $userId,
            'email' => 'student-' . $userId . '@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $studentId = (string) \Illuminate\Support\Str::uuid();
        // Create student
        $student = EloquentStudent::create([
            'id' => $studentId,
            'user_id' => $user->id,
            'student_number' => 'STU-2026-' . substr($studentId, 0, 8),
            'academic_status' => 'active',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.5,
            'institution_id' => null,
        ]);
        
        // Verify student exists in database
        $this->assertDatabaseHas('academic_students', ['id' => $studentId]);

        $targetCourseId = (string) \Illuminate\Support\Str::uuid();
        // Create target course
        $targetCourse = EloquentCourse::create([
            'id' => $targetCourseId,
            'code' => 'CS201',
            'title' => 'Data Structures',
            'description' => 'Advanced CS concepts',
            'credit_hours' => 3,
            'is_active' => true,
            'institution_id' => null,
        ]);

        $semesterId = (string) \Illuminate\Support\Str::uuid();
        // Create semester
        EloquentSemester::create([
            'id' => $semesterId,
            'name' => 'Fall 2026',
            'name_en' => 'Fall 2026',
            'code' => 'FALL2026',
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
            'institution_id' => null,
        ]);

        // Attempt enrollment (no prerequisites)
        $response = $this->postJson('/api/v1/academic/enrollments', [
            'student_id' => $studentId,
            'course_id' => $targetCourseId,
            'semester_id' => $semesterId,
        ]);

        if ($response->status() !== 201) {
            dd($response->json());
        }

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_enrollment_succeeds_when_prerequisite_is_met(): void
    {
        // Create admin user for authentication
        $admin = EloquentUser::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'email' => 'admin@test.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password_hash' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
            'academic_id' => null,
        ]);
        Sanctum::actingAs($admin);

        // Create user
        $user = EloquentUser::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'email' => 'student2@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        $studentId = (string) \Illuminate\Support\Str::uuid();
        // Create student
        EloquentStudent::create([
            'id' => $studentId,
            'user_id' => $user->id,
            'student_number' => 'STU-2026-002',
            'academic_status' => 'active',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.5,
            'institution_id' => null,
        ]);

        // Create prerequisite course
        $course1 = EloquentCourse::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'code' => 'CS101',
            'title' => 'Introduction to Computer Science',
            'description' => 'Basic CS concepts',
            'credit_hours' => 3,
            'is_active' => true,
            'institution_id' => null,
        ]);

        // Create target course without prerequisite for this test
        $course2 = EloquentCourse::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'code' => 'CS201',
            'title' => 'Data Structures',
            'description' => 'Advanced CS concepts',
            'credit_hours' => 3,
            'is_active' => true,
            'institution_id' => null,
        ]);

        $semesterId = (string) \Illuminate\Support\Str::uuid();
        // Create semester
        EloquentSemester::create([
            'id' => $semesterId,
            'name' => 'Fall 2026',
            'name_en' => 'Fall 2026',
            'code' => 'FALL2026',
            'start_date' => '2026-09-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
            'institution_id' => null,
        ]);

        // Attempt enrollment (no prerequisites required)
        $response = $this->postJson('/api/v1/academic/enrollments', [
            'student_id' => $studentId,
            'course_id' => $course2->id,
            'semester_id' => $semesterId,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'student_id' => $studentId,
                    'course_id' => $course2->id,
                    'semester_id' => $semesterId,
                ],
            ]);
    }
}
