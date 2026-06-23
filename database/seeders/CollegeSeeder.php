<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Shared\Infrastructure\Persistence\EloquentCollege;

final class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        $universities = DB::table('universities')->pluck('id', 'code');

        $colleges = [
            // جامعة صنعاء
            [
                'university_code' => 'SANA',
                'name' => 'كلية علوم الحاسب والمعلومات',
                'name_en' => 'College of Computer Science and Information',
                'code' => 'CSIS',
            ],
            [
                'university_code' => 'SANA',
                'name' => 'كلية الهندسة',
                'name_en' => 'College of Engineering',
                'code' => 'ENGN',
            ],
            [
                'university_code' => 'SANA',
                'name' => 'كلية الطب',
                'name_en' => 'College of Medicine',
                'code' => 'MEDC',
            ],
            [
                'university_code' => 'SANA',
                'name' => 'كلية العلوم',
                'name_en' => 'College of Science',
                'code' => 'SCNC',
            ],
            [
                'university_code' => 'SANA',
                'name' => 'كلية الإدارة',
                'name_en' => 'College of Administration',
                'code' => 'ADMC',
            ],
            // جامعة عدن
            [
                'university_code' => 'ADEN',
                'name' => 'كلية علوم الحاسب والمعلومات',
                'name_en' => 'College of Computer Science and Information',
                'code' => 'CSIA',
            ],
            [
                'university_code' => 'ADEN',
                'name' => 'كلية الهندسة',
                'name_en' => 'College of Engineering',
                'code' => 'ENGA',
            ],
            [
                'university_code' => 'ADEN',
                'name' => 'كلية الطب',
                'name_en' => 'College of Medicine',
                'code' => 'MEDA',
            ],
            [
                'university_code' => 'ADEN',
                'name' => 'كلية العلوم',
                'name_en' => 'College of Science',
                'code' => 'SCNA',
            ],
            [
                'university_code' => 'ADEN',
                'name' => 'كلية الإدارة',
                'name_en' => 'College of Administration',
                'code' => 'ADMA',
            ],
            // جامعة ذمار
            [
                'university_code' => 'DHAM',
                'name' => 'كلية علوم الحاسب والمعلومات',
                'name_en' => 'College of Computer Science and Information',
                'code' => 'CSID',
            ],
            [
                'university_code' => 'DHAM',
                'name' => 'كلية الهندسة',
                'name_en' => 'College of Engineering',
                'code' => 'ENGD',
            ],
            [
                'university_code' => 'DHAM',
                'name' => 'كلية الطب',
                'name_en' => 'College of Medicine',
                'code' => 'MEDD',
            ],
            [
                'university_code' => 'DHAM',
                'name' => 'كلية العلوم',
                'name_en' => 'College of Science',
                'code' => 'SCND',
            ],
            [
                'university_code' => 'DHAM',
                'name' => 'كلية الإدارة',
                'name_en' => 'College of Administration',
                'code' => 'ADMD',
            ],
            // جامعة الحديدة
            [
                'university_code' => 'HODE',
                'name' => 'كلية علوم الحاسب والمعلومات',
                'name_en' => 'College of Computer Science and Information',
                'code' => 'CSIH',
            ],
            [
                'university_code' => 'HODE',
                'name' => 'كلية الهندسة',
                'name_en' => 'College of Engineering',
                'code' => 'ENGH',
            ],
            [
                'university_code' => 'HODE',
                'name' => 'كلية الطب',
                'name_en' => 'College of Medicine',
                'code' => 'MEDH',
            ],
            [
                'university_code' => 'HODE',
                'name' => 'كلية العلوم',
                'name_en' => 'College of Science',
                'code' => 'SCNH',
            ],
            [
                'university_code' => 'HODE',
                'name' => 'كلية الإدارة',
                'name_en' => 'College of Administration',
                'code' => 'ADMH',
            ],
            // جامعة إب
            [
                'university_code' => 'IBB',
                'name' => 'كلية علوم الحاسب والمعلومات',
                'name_en' => 'College of Computer Science and Information',
                'code' => 'CSII',
            ],
            [
                'university_code' => 'IBB',
                'name' => 'كلية الهندسة',
                'name_en' => 'College of Engineering',
                'code' => 'ENGI',
            ],
            [
                'university_code' => 'IBB',
                'name' => 'كلية الطب',
                'name_en' => 'College of Medicine',
                'code' => 'MEDI',
            ],
            [
                'university_code' => 'IBB',
                'name' => 'كلية العلوم',
                'name_en' => 'College of Science',
                'code' => 'SCNI',
            ],
            [
                'university_code' => 'IBB',
                'name' => 'كلية الإدارة',
                'name_en' => 'College of Administration',
                'code' => 'ADMI',
            ],
        ];

        foreach ($colleges as $college) {
            $existing = DB::table('colleges')
                ->where('code', $college['code'])
                ->first();

            if ($existing === null) {
                $universityId = $universities[$college['university_code']] ?? null;

                if ($universityId !== null) {
                    EloquentCollege::create([
                        'university_id' => $universityId,
                        'name' => $college['name'],
                        'name_en' => $college['name_en'],
                        'code' => $college['code'],
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
