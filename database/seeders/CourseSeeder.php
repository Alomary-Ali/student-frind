<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Infrastructure\Persistence\EloquentCourse;

final class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            // Level 1 Courses
            [
                'code' => 'CS101',
                'title' => 'مقدمة في علوم الحاسب',
                'title_en' => 'Introduction to Computer Science',
                'description' => 'مقدمة في مفاهيم علوم الحاسب الأساسية',
                'credit_hours' => 3,
                'prerequisites' => [],
            ],
            [
                'code' => 'CS102',
                'title' => 'برمجة 1',
                'title_en' => 'Programming 1',
                'description' => 'مقدمة في البرمجة باستخدام Python',
                'credit_hours' => 4,
                'prerequisites' => [],
            ],
            [
                'code' => 'MATH101',
                'title' => 'الرياضيات 1',
                'title_en' => 'Mathematics 1',
                'description' => 'حساب التفاضل والتكامل 1',
                'credit_hours' => 3,
                'prerequisites' => [],
            ],
            [
                'code' => 'ENG101',
                'title' => 'اللغة الإنجليزية 1',
                'title_en' => 'English Language 1',
                'description' => 'مهارات اللغة الإنجليزية الأساسية',
                'credit_hours' => 3,
                'prerequisites' => [],
            ],
            // Level 2 Courses
            [
                'code' => 'CS201',
                'title' => 'هياكل البيانات',
                'title_en' => 'Data Structures',
                'description' => 'هياكل البيانات والخوارزميات',
                'credit_hours' => 3,
                'prerequisites' => ['CS102'],
            ],
            [
                'code' => 'CS202',
                'title' => 'برمجة 2',
                'title_en' => 'Programming 2',
                'description' => 'البرمجة المتقدمة باستخدام Java',
                'credit_hours' => 4,
                'prerequisites' => ['CS102'],
            ],
            [
                'code' => 'MATH201',
                'title' => 'الرياضيات 2',
                'title_en' => 'Mathematics 2',
                'description' => 'حساب التفاضل والتكامل 2',
                'credit_hours' => 3,
                'prerequisites' => ['MATH101'],
            ],
            [
                'code' => 'CS203',
                'title' => 'قواعد البيانات',
                'title_en' => 'Databases',
                'description' => 'مقدمة في قواعد البيانات',
                'credit_hours' => 3,
                'prerequisites' => ['CS102'],
            ],
            // Level 3 Courses
            [
                'code' => 'CS301',
                'title' => 'أنظمة التشغيل',
                'title_en' => 'Operating Systems',
                'description' => 'مبادئ أنظمة التشغيل',
                'credit_hours' => 3,
                'prerequisites' => ['CS201'],
            ],
            [
                'code' => 'CS302',
                'title' => 'الشبكات',
                'title_en' => 'Networks',
                'description' => 'أساسيات شبكات الحاسب',
                'credit_hours' => 3,
                'prerequisites' => ['CS201'],
            ],
            [
                'code' => 'CS303',
                'title' => 'هندسة البرمجيات',
                'title_en' => 'Software Engineering',
                'description' => 'مبادئ هندسة البرمجيات',
                'credit_hours' => 3,
                'prerequisites' => ['CS202'],
            ],
            [
                'code' => 'CS304',
                'title' => 'الأمن السيبراني',
                'title_en' => 'Cyber Security',
                'description' => 'مقدمة في الأمن السيبراني',
                'credit_hours' => 3,
                'prerequisites' => ['CS302'],
            ],
            // Level 4 Courses
            [
                'code' => 'CS401',
                'title' => 'الذكاء الاصطناعي',
                'title_en' => 'Artificial Intelligence',
                'description' => 'مقدمة في الذكاء الاصطناعي',
                'credit_hours' => 3,
                'prerequisites' => ['CS301'],
            ],
            [
                'code' => 'CS402',
                'title' => 'تعلم الآلة',
                'title_en' => 'Machine Learning',
                'description' => 'خوارزميات تعلم الآلة',
                'credit_hours' => 3,
                'prerequisites' => ['CS401'],
            ],
            [
                'code' => 'CS403',
                'title' => 'مشروع التخرج 1',
                'title_en' => 'Graduation Project 1',
                'description' => 'مشروع تخرج جزء 1',
                'credit_hours' => 3,
                'prerequisites' => ['CS303'],
            ],
            [
                'code' => 'CS404',
                'title' => 'مشروع التخرج 2',
                'title_en' => 'Graduation Project 2',
                'description' => 'مشروع تخرج جزء 2',
                'credit_hours' => 3,
                'prerequisites' => ['CS403'],
            ],
        ];

        foreach ($courses as $course) {
            $existing = DB::table('academic_courses')
                ->where('code', $course['code'])
                ->first();

            if ($existing === null) {
                EloquentCourse::create([
                    'code' => $course['code'],
                    'title' => $course['title'],
                    'description' => $course['description'],
                    'credit_hours' => $course['credit_hours'],
                    'is_active' => true,
                    'institution_id' => null,
                ]);
            }
        }
    }
}
