<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Shared\Infrastructure\Persistence\EloquentUniversity;

final class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        $universities = [
            [
                'name' => 'جامعة صنعاء',
                'name_en' => 'Sana\'a University',
                'code' => 'SANA',
                'is_active' => true,
            ],
            [
                'name' => 'جامعة عدن',
                'name_en' => 'Aden University',
                'code' => 'ADEN',
                'is_active' => true,
            ],
            [
                'name' => 'جامعة ذمار',
                'name_en' => 'Dhamar University',
                'code' => 'DHAM',
                'is_active' => true,
            ],
            [
                'name' => 'جامعة الحديدة',
                'name_en' => 'Hodeidah University',
                'code' => 'HODE',
                'is_active' => true,
            ],
            [
                'name' => 'جامعة إب',
                'name_en' => 'Ibb University',
                'code' => 'IBB',
                'is_active' => true,
            ],
        ];

        foreach ($universities as $university) {
            $existing = DB::table('universities')
                ->where('code', $university['code'])
                ->first();

            if ($existing === null) {
                EloquentUniversity::create($university);
            }
        }
    }
}
