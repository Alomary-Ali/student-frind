<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignment;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentExam;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentProject;

final class ProductivitySeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->first()->id ?? null;

        if ($userId) {
            $this->seedAssignments($userId);
            $this->seedExams($userId);
            $this->seedProjects($userId);
        }
    }

    private function seedAssignments(string $userId): void
    {
        EloquentAssignment::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'course_id' => 'CS101',
            'title' => 'واجب البرمجة الأساسية',
            'description' => 'حل مسائل البرمجة الأساسية',
            'assigned_at' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'assigned',
            'grade' => null,
            'submission_url' => null,
        ]);

        EloquentAssignment::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'course_id' => 'MATH201',
            'title' => 'واجب الرياضيات المتقدمة',
            'description' => 'حل مسائل الرياضيات المتقدمة',
            'assigned_at' => now()->subDays(5),
            'due_date' => now()->addDays(3),
            'status' => 'in_progress',
            'grade' => null,
            'submission_url' => null,
        ]);
    }

    private function seedExams(string $userId): void
    {
        EloquentExam::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'course_id' => 'CS101',
            'title' => 'اختبار منتصف الفصل - البرمجة',
            'exam_type' => 'midterm',
            'exam_date' => now()->addDays(14),
            'location' => 'قاعة A',
            'status' => 'scheduled',
        ]);

        EloquentExam::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'course_id' => 'MATH201',
            'title' => 'اختبار نهائي - الرياضيات',
            'exam_type' => 'final',
            'exam_date' => now()->addDays(30),
            'location' => 'قاعة B',
            'status' => 'scheduled',
        ]);
    }

    private function seedProjects(string $userId): void
    {
        EloquentProject::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'title' => 'مشروع تطوير تطبيق الويب',
            'description' => 'تطوير تطبيق ويب لإدارة المهام',
            'start_date' => now(),
            'due_date' => now()->addDays(60),
            'status' => 'planning',
            'progress_percentage' => 0,
        ]);

        EloquentProject::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'title' => 'مشروع البحث العلمي',
            'description' => 'إجراء بحث في مجال الذكاء الاصطناعي',
            'start_date' => now()->subDays(10),
            'due_date' => now()->addDays(45),
            'status' => 'in_progress',
            'progress_percentage' => 35,
        ]);
    }
}
