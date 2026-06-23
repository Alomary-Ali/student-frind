<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicAlert;

final class AcademicAlertSeeder extends Seeder
{
    public function run(): void
    {
        $students = DB::table('academic_students')->pluck('id', 'user_id');

        $alerts = [
            // No alerts for existing student (new student)
        ];

        foreach ($alerts as $alert) {
            $studentId = $students[$alert['user_id']] ?? null;

            if ($studentId === null) {
                continue;
            }

            $existing = DB::table('academic_advisory_alerts')
                ->where('student_id', $studentId)
                ->where('alert_type', $alert['type']->value)
                ->where('is_resolved', false)
                ->first();

            if ($existing === null) {
                EloquentAcademicAlert::create([
                    'student_id' => $studentId,
                    'alert_type' => $alert['type']->value,
                    'severity' => $alert['severity']->value,
                    'message' => $alert['message'],
                    'is_resolved' => false,
                    'resolved_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
