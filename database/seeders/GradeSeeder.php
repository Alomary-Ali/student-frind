<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicRecord;

final class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $students = DB::table('academic_students')->pluck('id', 'user_id');
        $courses = DB::table('academic_courses')->pluck('id', 'code');
        $semester = DB::table('academic_semesters')
            ->where('code', '2024-2025-1')
            ->first();

        if ($semester === null) {
            return;
        }

        $grades = [
            // Student 20210001 (No grades yet - new student)
        ];

        $gradePoints = [
            'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7, 'D+' => 1.3, 'D' => 1.0, 'F' => 0.0,
        ];

        foreach ($grades as $gradeData) {
            $studentId = $students[$gradeData['user_id']] ?? null;

            if ($studentId === null) {
                continue;
            }

            foreach ($gradeData['grades'] as $courseCode => $gradeLetter) {
                $courseId = $courses[$courseCode] ?? null;

                if ($courseId === null) {
                    continue;
                }

                // Find enrollment
                $enrollment = DB::table('academic_enrollments')
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->where('semester_id', $semester->id)
                    ->first();

                if ($enrollment === null) {
                    continue;
                }

                $existing = DB::table('academic_records')
                    ->where('enrollment_id', $enrollment->id)
                    ->first();

                if ($existing === null) {
                    EloquentAcademicRecord::create([
                        'enrollment_id' => $enrollment->id,
                        'student_id' => $studentId,
                        'course_id' => $courseId,
                        'grade_letter' => $gradeLetter,
                        'grade_points' => $gradePoints[$gradeLetter],
                        'recorded_at' => now(),
                        'recorded_by_user_id' => null,
                    ]);
                }
            }
        }
    }
}
