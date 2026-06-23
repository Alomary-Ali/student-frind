<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Domain\Enums\EnrollmentStatus;
use Modules\Academic\Infrastructure\Persistence\EloquentEnrollment;

final class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = DB::table('academic_students')->pluck('id', 'user_id');
        $courses = DB::table('academic_courses')->pluck('id', 'code');
        $semester = DB::table('academic_semesters')
            ->where('code', '2025-2026-1')
            ->first();

        if ($semester === null) {
            return;
        }

        $enrollments = [
            // Student 20210001 (Level 1)
            [
                'user_id' => '20210001',
                'course_codes' => ['CS101', 'CS102', 'MATH101', 'ENG101'],
            ],
        ];

        foreach ($enrollments as $enrollment) {
            $studentId = $students[$enrollment['user_id']] ?? null;

            if ($studentId === null) {
                continue;
            }

            foreach ($enrollment['course_codes'] as $courseCode) {
                $courseId = $courses[$courseCode] ?? null;

                if ($courseId === null) {
                    continue;
                }

                $existing = DB::table('academic_enrollments')
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->where('semester_id', $semester->id)
                    ->first();

                if ($existing === null) {
                    EloquentEnrollment::create([
                        'student_id' => $studentId,
                        'course_id' => $courseId,
                        'semester_id' => $semester->id,
                        'status' => EnrollmentStatus::Active->value,
                        'enrolled_at' => now(),
                    ]);
                }
            }
        }
    }
}
