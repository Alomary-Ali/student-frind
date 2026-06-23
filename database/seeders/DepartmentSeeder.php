<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Shared\Infrastructure\Persistence\EloquentDepartment;

final class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $colleges = DB::table('colleges')->pluck('id', 'code');

        $departments = [
            // كلية علوم الحاسب والمعلومات - جامعة صنعاء
            [
                'college_code' => 'CSIS',
                'name' => 'قسم علوم الحاسب',
                'name_en' => 'Computer Science Department',
                'code' => 'CSCS',
            ],
            [
                'college_code' => 'CSIS',
                'name' => 'قسم هندسة البرمجيات',
                'name_en' => 'Software Engineering Department',
                'code' => 'SWE',
            ],
            [
                'college_code' => 'CSIS',
                'name' => 'قسم الشبكات',
                'name_en' => 'Networks Department',
                'code' => 'NET',
            ],
            [
                'college_code' => 'CSIS',
                'name' => 'قسم الأمن السيبراني',
                'name_en' => 'Cyber Security Department',
                'code' => 'SEC',
            ],
            [
                'college_code' => 'CSIS',
                'name' => 'قسم الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence Department',
                'code' => 'AI',
            ],
            // كلية علوم الحاسب والمعلومات - جامعة عدن
            [
                'college_code' => 'CSIA',
                'name' => 'قسم علوم الحاسب',
                'name_en' => 'Computer Science Department',
                'code' => 'CSCA',
            ],
            [
                'college_code' => 'CSIA',
                'name' => 'قسم هندسة البرمجيات',
                'name_en' => 'Software Engineering Department',
                'code' => 'SWA',
            ],
            [
                'college_code' => 'CSIA',
                'name' => 'قسم الشبكات',
                'name_en' => 'Networks Department',
                'code' => 'NTA',
            ],
            [
                'college_code' => 'CSIA',
                'name' => 'قسم الأمن السيبراني',
                'name_en' => 'Cyber Security Department',
                'code' => 'SCA',
            ],
            [
                'college_code' => 'CSIA',
                'name' => 'قسم الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence Department',
                'code' => 'AIA',
            ],
            // كلية علوم الحاسب والمعلومات - جامعة ذمار
            [
                'college_code' => 'CSID',
                'name' => 'قسم علوم الحاسب',
                'name_en' => 'Computer Science Department',
                'code' => 'CSCD',
            ],
            [
                'college_code' => 'CSID',
                'name' => 'قسم هندسة البرمجيات',
                'name_en' => 'Software Engineering Department',
                'code' => 'SWD',
            ],
            [
                'college_code' => 'CSID',
                'name' => 'قسم الشبكات',
                'name_en' => 'Networks Department',
                'code' => 'NTD',
            ],
            [
                'college_code' => 'CSID',
                'name' => 'قسم الأمن السيبراني',
                'name_en' => 'Cyber Security Department',
                'code' => 'SCD',
            ],
            [
                'college_code' => 'CSID',
                'name' => 'قسم الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence Department',
                'code' => 'AID',
            ],
            // كلية علوم الحاسب والمعلومات - جامعة الحديدة
            [
                'college_code' => 'CSIH',
                'name' => 'قسم علوم الحاسب',
                'name_en' => 'Computer Science Department',
                'code' => 'CSCH',
            ],
            [
                'college_code' => 'CSIH',
                'name' => 'قسم هندسة البرمجيات',
                'name_en' => 'Software Engineering Department',
                'code' => 'SWH',
            ],
            [
                'college_code' => 'CSIH',
                'name' => 'قسم الشبكات',
                'name_en' => 'Networks Department',
                'code' => 'NTH',
            ],
            [
                'college_code' => 'CSIH',
                'name' => 'قسم الأمن السيبراني',
                'name_en' => 'Cyber Security Department',
                'code' => 'SCH',
            ],
            [
                'college_code' => 'CSIH',
                'name' => 'قسم الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence Department',
                'code' => 'AIH',
            ],
            // كلية علوم الحاسب والمعلومات - جامعة إب
            [
                'college_code' => 'CSII',
                'name' => 'قسم علوم الحاسب',
                'name_en' => 'Computer Science Department',
                'code' => 'CSCI',
            ],
            [
                'college_code' => 'CSII',
                'name' => 'قسم هندسة البرمجيات',
                'name_en' => 'Software Engineering Department',
                'code' => 'SWI',
            ],
            [
                'college_code' => 'CSII',
                'name' => 'قسم الشبكات',
                'name_en' => 'Networks Department',
                'code' => 'NTI',
            ],
            [
                'college_code' => 'CSII',
                'name' => 'قسم الأمن السيبراني',
                'name_en' => 'Cyber Security Department',
                'code' => 'SCI',
            ],
            [
                'college_code' => 'CSII',
                'name' => 'قسم الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence Department',
                'code' => 'AII',
            ],
        ];

        foreach ($departments as $department) {
            $existing = DB::table('departments')
                ->where('code', $department['code'])
                ->first();

            if ($existing === null) {
                $collegeId = $colleges[$department['college_code']] ?? null;

                if ($collegeId !== null) {
                    EloquentDepartment::create([
                        'college_id' => $collegeId,
                        'name' => $department['name'],
                        'name_en' => $department['name_en'],
                        'code' => $department['code'],
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
