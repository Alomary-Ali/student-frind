<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class StudentDemoSeeder extends Seeder
{
    private const GRADE_POINTS = [
        'A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
        'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7, 'D+' => 1.3, 'D' => 1.0, 'F' => 0.0,
    ];

    public function run(): void
    {
        // ── 1. Find reference data ──────────────────────────────────────────
        $user = DB::table('users')->where('academic_id', '20210001')->first();
        if ($user === null) {
            $this->command->error('User 20210001 not found. Run TestUsersSeeder first.');
            return;
        }

        $userId = $user->id;

        $student = DB::table('academic_students')->where('user_id', $userId)->first();
        if ($student === null) {
            $this->command->error('Academic student record not found for user 20210001.');
            return;
        }

        $courses = DB::table('academic_courses')->pluck('id', 'code');
        $semesters = DB::table('academic_semesters')->pluck('id', 'code');

        $sem1 = $semesters['2024-2025-1'] ?? null;
        $sem2 = $semesters['2024-2025-2'] ?? null;
        $semCurrent = $semesters['2025-2026-1'] ?? null;

        if ($sem1 === null || $sem2 === null || $semCurrent === null) {
            $this->command->error('Required semesters not found. Run SemesterSeeder first.');
            return;
        }

        // ── 2. Update student profile ────────────────────────────────────────
        DB::table('academic_students')
            ->where('id', $student->id)
            ->update([
                'cumulative_gpa' => 3.20,
                'semester_gpa' => 3.40,
                'level' => '4',
                'current_semester_id' => $semCurrent,
                'updated_at' => now(),
            ]);

        $this->command->info('✓ Student profile updated to level 4, GPA 3.20');

        // ── 3. Ensure curriculum exists ─────────────────────────────────────
        $curriculum = DB::table('academic_curricula')
            ->where('code', 'CS-BSC')
            ->first();

        if ($curriculum === null) {
            $curriculumId = (string) Str::uuid();
            DB::table('academic_curricula')->insert([
                'id' => $curriculumId,
                'name' => 'بكالوريوس علوم الحاسب',
                'code' => 'CS-BSC',
                'description' => 'برنامج بكالوريوس علوم الحاسب - 4 سنوات',
                'total_credits_required' => 132,
                'institution_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $curriculum = (object) ['id' => $curriculumId];
        }

        // ── 4. Create graduation path ────────────────────────────────────────
        $existingPath = DB::table('academic_graduation_paths')
            ->where('student_id', $student->id)
            ->first();

        if ($existingPath === null) {
            DB::table('academic_graduation_paths')->insert([
                'id' => (string) Str::uuid(),
                'student_id' => $student->id,
                'curriculum_id' => $curriculum->id,
                'credits_earned' => 96,
                'credits_required' => 132,
                'completion_percentage' => 72.73,
                'is_on_track' => true,
                'estimated_graduation_date' => '2027-06-01',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('✓ Graduation path created (96/132 credits, 72.7%)');
        }

        // ── 4. Semester 1 — Complete with grades ────────────────────────────
        $sem1Courses = [
            ['code' => 'CS101',  'grade' => 'A',  'points' => 4.0],
            ['code' => 'CS102',  'grade' => 'B+', 'points' => 3.3],
            ['code' => 'MATH101','grade' => 'A-', 'points' => 3.7],
            ['code' => 'ENG101', 'grade' => 'B',  'points' => 3.0],
        ];

        foreach ($sem1Courses as $c) {
            $courseId = $courses[$c['code']] ?? null;
            if ($courseId === null) continue;

            $enrollment = DB::table('academic_enrollments')
                ->where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->where('semester_id', $sem1)
                ->first();

            if ($enrollment === null) {
                $enrollmentId = (string) Str::uuid();
                DB::table('academic_enrollments')->insert([
                    'id' => $enrollmentId,
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'semester_id' => $sem1,
                    'status' => 'completed',
                    'enrolled_at' => '2024-09-01 08:00:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $enrollment = (object) ['id' => $enrollmentId];
            } else {
                DB::table('academic_enrollments')
                    ->where('id', $enrollment->id)
                    ->update(['status' => 'completed', 'updated_at' => now()]);
            }

            $existing = DB::table('academic_records')
                ->where('enrollment_id', $enrollment->id)
                ->first();

            if ($existing === null) {
                DB::table('academic_records')->insert([
                    'id' => (string) Str::uuid(),
                    'enrollment_id' => $enrollment->id,
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'grade_letter' => $c['grade'],
                    'grade_points' => $c['points'],
                    'recorded_at' => '2025-01-20 14:00:00',
                    'recorded_by_user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ Semester 1 completed: CS101(A), CS102(B+), MATH101(A-), ENG101(B)');

        // ── 5. Semester 2 — Complete with grades ────────────────────────────
        $sem2Courses = [
            ['code' => 'CS201',  'grade' => 'B+', 'points' => 3.3],
            ['code' => 'CS202',  'grade' => 'B',  'points' => 3.0],
            ['code' => 'MATH201','grade' => 'B-', 'points' => 2.7],
            ['code' => 'CS203',  'grade' => 'A-', 'points' => 3.7],
        ];

        foreach ($sem2Courses as $c) {
            $courseId = $courses[$c['code']] ?? null;
            if ($courseId === null) continue;

            $enrollment = DB::table('academic_enrollments')
                ->where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->where('semester_id', $sem2)
                ->first();

            if ($enrollment === null) {
                $enrollmentId = (string) Str::uuid();
                DB::table('academic_enrollments')->insert([
                    'id' => $enrollmentId,
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'semester_id' => $sem2,
                    'status' => 'completed',
                    'enrolled_at' => '2025-02-01 08:00:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $enrollment = (object) ['id' => $enrollmentId];
            } else {
                DB::table('academic_enrollments')
                    ->where('id', $enrollment->id)
                    ->update(['status' => 'completed', 'updated_at' => now()]);
            }

            $existing = DB::table('academic_records')
                ->where('enrollment_id', $enrollment->id)
                ->first();

            if ($existing === null) {
                DB::table('academic_records')->insert([
                    'id' => (string) Str::uuid(),
                    'enrollment_id' => $enrollment->id,
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'grade_letter' => $c['grade'],
                    'grade_points' => $c['points'],
                    'recorded_at' => '2025-07-05 14:00:00',
                    'recorded_by_user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ Semester 2 completed: CS201(B+), CS202(B), MATH201(B-), CS203(A-)');

        // ── 6. Current semester — Active enrollments (Level 3 courses) ──────
        $currentCourses = ['CS301', 'CS302', 'CS303', 'CS304'];

        foreach ($currentCourses as $code) {
            $courseId = $courses[$code] ?? null;
            if ($courseId === null) continue;

            $enrollment = DB::table('academic_enrollments')
                ->where('student_id', $student->id)
                ->where('course_id', $courseId)
                ->where('semester_id', $semCurrent)
                ->first();

            if ($enrollment === null) {
                DB::table('academic_enrollments')->insert([
                    'id' => (string) Str::uuid(),
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'semester_id' => $semCurrent,
                    'status' => 'enrolled',
                    'enrolled_at' => '2025-09-01 08:00:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ Current semester enrollment: CS301, CS302, CS303, CS304');

        // ── 7. Academic alerts ──────────────────────────────────────────────
        $alerts = [
            [
                'alert_type' => 'academic_risk',
                'severity' => 'medium',
                'message' => 'معدلك التراكمي 3.20 يمكن تحسينه إلى 3.5+ هذا الفصل. ركز على المواد ذات الساعات المعتمدة العالية.',
                'metadata' => json_encode(['current_gpa' => 3.20, 'target_gpa' => 3.50]),
            ],
            [
                'alert_type' => 'graduation_delay',
                'severity' => 'low',
                'message' => 'أنت في المستوى الرابع. تبقى 36 ساعة معتمدة لإكمال متطلبات التخرج. احرص على التسجيل المنتظم.',
                'metadata' => json_encode(['remaining_credits' => 36, 'current_level' => 4]),
            ],
            [
                'alert_type' => 'credit_deficit',
                'severity' => 'low',
                'message' => 'لديك 3 مواد مؤجلة يمكن تسجيلها في الفصل القادم: CS401 الذكاء الاصطناعي, CS402 تعلم الآلة, CS403 مشروع التخرج.',
                'metadata' => json_encode(['deferred_courses' => ['CS401', 'CS402', 'CS403']]),
            ],
        ];

        foreach ($alerts as $alert) {
            $existing = DB::table('academic_advisory_alerts')
                ->where('student_id', $student->id)
                ->where('alert_type', $alert['alert_type'])
                ->where('is_resolved', false)
                ->first();

            if ($existing === null) {
                DB::table('academic_advisory_alerts')->insert([
                    'id' => (string) Str::uuid(),
                    'student_id' => $student->id,
                    'alert_type' => $alert['alert_type'],
                    'severity' => $alert['severity'],
                    'message' => $alert['message'],
                    'metadata' => $alert['metadata'],
                    'is_resolved' => false,
                    'resolved_at' => null,
                    'resolved_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ 3 academic alerts created');

        // ── 8. Productivity — Goals ────────────────────────────────────────
        $goalGradProjectId = (string) Str::uuid();
        $goalGpaId = (string) Str::uuid();
        $goalMastersId = (string) Str::uuid();

        $goals = [
            [
                'id' => $goalGradProjectId,
                'title' => 'إتمام مشروع التخرج بنجاح',
                'description' => 'إكمال مشروع التخرج في تطبيق ويب لإدارة المبادرات الطلابية باستخدام Laravel و React',
                'target_date' => '2026-05-01',
                'priority' => 'high',
                'progress' => 35.00,
                'status' => 'active',
            ],
            [
                'id' => $goalGpaId,
                'title' => 'رفع المعدل التراكمي إلى 3.5',
                'description' => 'تحقيق معدل فصلي 3.7+ في الفصل الحالي لرفع المعدل التراكمي',
                'target_date' => '2026-02-01',
                'priority' => 'high',
                'progress' => 0.00,
                'status' => 'active',
            ],
            [
                'id' => $goalMastersId,
                'title' => 'التقديم على برامج الماجستير',
                'description' => 'البحث والتقديم على برامج الماجستير في جامعات محلية ودولية',
                'target_date' => '2026-08-01',
                'priority' => 'medium',
                'progress' => 10.00,
                'status' => 'active',
            ],
        ];

        foreach ($goals as $g) {
            $existing = DB::table('productivity_goals')
                ->where('id', $g['id'])
                ->first();

            if ($existing === null) {
                DB::table('productivity_goals')->insert([
                    'id' => $g['id'],
                    'user_id' => $user->id,
                    'title' => $g['title'],
                    'description' => $g['description'],
                    'target_date' => $g['target_date'],
                    'priority' => $g['priority'],
                    'progress' => $g['progress'],
                    'status' => $g['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ 3 productivity goals created');

        // ── 9. Productivity — Tasks ─────────────────────────────────────────
        $tasks = [
            [
                'title' => 'تحضير عرض مشروع التخرج',
                'description' => 'تجهيز عرض PowerPoint لمشروع التخرج يشمل المقدمة، المشكلة، الحل المقترح',
                'due_date' => '2026-01-20 23:59:00',
                'priority' => 'high',
                'status' => 'in_progress',
                'linked_goal_id' => $goalGradProjectId,
            ],
            [
                'title' => 'حل واجب OS - العمليات',
                'description' => 'حل التمارين 3.1 إلى 3.8 من كتاب Operating Systems Concepts',
                'due_date' => '2026-01-10 23:59:00',
                'priority' => 'medium',
                'status' => 'pending',
                'linked_goal_id' => null,
            ],
            [
                'title' => 'مراجعة شبكات - الفصل الثالث',
                'description' => 'مراجعة شاملة للفصل الثالث: Network Layer و IP Addressing',
                'due_date' => '2026-01-20 23:59:00',
                'priority' => 'medium',
                'status' => 'pending',
                'linked_goal_id' => null,
            ],
            [
                'title' => 'كتابة تقرير هندسة البرمجيات',
                'description' => 'تسليم التقرير الأسبوعي لمادة هندسة البرمجيات عن متطلبات النظام',
                'due_date' => '2026-01-15 23:59:00',
                'priority' => 'high',
                'status' => 'in_progress',
                'linked_goal_id' => $goalGradProjectId,
            ],
            [
                'title' => 'مذاكرة اختبار أمن سيبراني',
                'description' => 'الاستعداد لاختبار منتصف الفصل: تغطية Cryptography و Network Security Fundamentals',
                'due_date' => '2026-01-25 23:59:00',
                'priority' => 'high',
                'status' => 'pending',
                'linked_goal_id' => null,
            ],
            [
                'title' => 'تقديم طلب تخرج للكلية',
                'description' => 'تقديم نموذج طلب التخرج للكلية مع الوثائق المطلوبة',
                'due_date' => '2026-03-01 23:59:00',
                'priority' => 'low',
                'status' => 'completed',
                'linked_goal_id' => $goalMastersId,
                'completed_at' => '2025-12-15 10:00:00',
            ],
        ];

        foreach ($tasks as $t) {
            $taskId = (string) Str::uuid();
            $existing = DB::table('productivity_tasks')
                ->where('title', $t['title'])
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->first();

            if ($existing === null) {
                $data = [
                    'id' => $taskId,
                    'user_id' => $user->id,
                    'title' => $t['title'],
                    'description' => $t['description'],
                    'due_date' => $t['due_date'],
                    'priority' => $t['priority'],
                    'status' => $t['status'],
                    'linked_goal_id' => $t['linked_goal_id'],
                    'completed_at' => $t['completed_at'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                DB::table('productivity_tasks')->insert($data);
            }
        }
        $this->command->info('✓ 6 productivity tasks created');

        // ── 10. Productivity — Calendar Events ──────────────────────────────
        $events = [
            [
                'title' => 'اختبار OS منتصف الفصل',
                'description' => 'اختبار أنظمة التشغيل يشمل: إدارة العمليات، الجدولة، المزامنة',
                'starts_at' => '2026-01-15 09:00:00',
                'ends_at' => '2026-01-15 11:00:00',
                'is_all_day' => false,
            ],
            [
                'title' => 'موعد تسليم مشروع Networks',
                'description' => 'تسليم مشروع الشبكة باستخدام Cisco Packet Tracer',
                'starts_at' => '2026-02-01 23:59:00',
                'ends_at' => '2026-02-02 00:00:00',
                'is_all_day' => false,
            ],
            [
                'title' => 'عرض مشروع التخرج - مسودة أولى',
                'description' => 'تقديم المسودة الأولى لمشروع التخرج للمشرف',
                'starts_at' => '2026-02-10 10:00:00',
                'ends_at' => '2026-02-10 12:00:00',
                'is_all_day' => false,
            ],
            [
                'title' => 'اختبار SW Engineering نهائي',
                'description' => 'الاختبار النهائي لهندسة البرمجيات',
                'starts_at' => '2026-03-05 09:00:00',
                'ends_at' => '2026-03-05 11:00:00',
                'is_all_day' => false,
            ],
        ];

        foreach ($events as $e) {
            $existing = DB::table('productivity_calendar_events')
                ->where('title', $e['title'])
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->first();

            if ($existing === null) {
                DB::table('productivity_calendar_events')->insert([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'title' => $e['title'],
                    'description' => $e['description'],
                    'starts_at' => $e['starts_at'],
                    'ends_at' => $e['ends_at'],
                    'is_all_day' => $e['is_all_day'],
                    'linked_task_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ 4 calendar events created');

        // ── 11. Productivity — Reminders ────────────────────────────────────
        $reminders = [
            [
                'message' => 'تذكير: تسليم تقرير هندسة البرمجيات بعد 3 أيام',
                'trigger_at' => '2026-01-12 09:00:00',
                'type' => 'in_app',
                'status' => 'pending',
            ],
            [
                'message' => 'تجهيز وثائق التقديم على الماجستير',
                'trigger_at' => '2026-02-01 09:00:00',
                'type' => 'in_app',
                'status' => 'pending',
            ],
            [
                'message' => 'مراجعة جدول الاختبارات النهائية',
                'trigger_at' => '2026-02-20 09:00:00',
                'type' => 'in_app',
                'status' => 'pending',
            ],
        ];

        foreach ($reminders as $r) {
            $existing = DB::table('productivity_reminders')
                ->where('message', $r['message'])
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->first();

            if ($existing === null) {
                DB::table('productivity_reminders')->insert([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'message' => $r['message'],
                    'trigger_at' => $r['trigger_at'],
                    'type' => $r['type'],
                    'linked_task_id' => null,
                    'status' => $r['status'],
                    'triggered_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ 3 reminders created');

        // ── 12. Productivity — Snapshots (monthly for last 3 months) ────────
        $snapshots = [
            [
                'snapshot_date' => '2025-10-01',
                'total_goals' => 3, 'completed_goals' => 0,
                'total_tasks' => 4, 'completed_tasks' => 0, 'overdue_tasks' => 1,
                'completion_rate' => 0.00,
            ],
            [
                'snapshot_date' => '2025-11-01',
                'total_goals' => 3, 'completed_goals' => 0,
                'total_tasks' => 5, 'completed_tasks' => 1, 'overdue_tasks' => 1,
                'completion_rate' => 20.00,
            ],
            [
                'snapshot_date' => '2025-12-01',
                'total_goals' => 3, 'completed_goals' => 0,
                'total_tasks' => 6, 'completed_tasks' => 1, 'overdue_tasks' => 2,
                'completion_rate' => 16.67,
            ],
        ];

        foreach ($snapshots as $s) {
            $existing = DB::table('productivity_snapshots')
                ->where('user_id', $user->id)
                ->where('snapshot_date', $s['snapshot_date'])
                ->first();

            if ($existing === null) {
                DB::table('productivity_snapshots')->insert([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'total_goals' => $s['total_goals'],
                    'completed_goals' => $s['completed_goals'],
                    'total_tasks' => $s['total_tasks'],
                    'completed_tasks' => $s['completed_tasks'],
                    'overdue_tasks' => $s['overdue_tasks'],
                    'completion_rate' => $s['completion_rate'],
                    'snapshot_date' => $s['snapshot_date'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->command->info('✓ 3 monthly productivity snapshots created');

        $this->command->info('───');
        $this->command->info('Student demo seeding complete for محمد الأحمدي (20210001) ✅');
    }
}
