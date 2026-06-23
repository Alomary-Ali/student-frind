<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Infrastructure\Persistence\EloquentSemester;

final class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            [
                'name' => 'الفصل الأول 2024-2025',
                'name_en' => 'First Semester 2024-2025',
                'code' => '2024-2025-1',
                'start_date' => '2024-09-01',
                'end_date' => '2025-01-15',
                'is_active' => false,
            ],
            [
                'name' => 'الفصل الثاني 2024-2025',
                'name_en' => 'Second Semester 2024-2025',
                'code' => '2024-2025-2',
                'start_date' => '2025-02-01',
                'end_date' => '2025-06-30',
                'is_active' => false,
            ],
            [
                'name' => 'الفصل الصيفي 2024-2025',
                'name_en' => 'Summer Semester 2024-2025',
                'code' => '2024-2025-S',
                'start_date' => '2025-07-01',
                'end_date' => '2025-08-31',
                'is_active' => false,
            ],
            [
                'name' => 'الفصل الأول 2025-2026',
                'name_en' => 'First Semester 2025-2026',
                'code' => '2025-2026-1',
                'start_date' => '2025-09-01',
                'end_date' => '2026-01-15',
                'is_active' => true,
            ],
            [
                'name' => 'الفصل الثاني 2025-2026',
                'name_en' => 'Second Semester 2025-2026',
                'code' => '2025-2026-2',
                'start_date' => '2026-02-01',
                'end_date' => '2026-06-30',
                'is_active' => false,
            ],
        ];

        foreach ($semesters as $semester) {
            $existing = DB::table('academic_semesters')
                ->where('code', $semester['code'])
                ->first();

            if ($existing === null) {
                EloquentSemester::create($semester);
            }
        }
    }
}
