<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Shared\Infrastructure\Persistence\EloquentMajor;

final class MajorSeeder extends Seeder
{
    public function run(): void
    {
        $departments = DB::table('departments')->pluck('id', 'code');

        $majors = [
            // قسم علوم الحاسب - جامعة صنعاء
            [
                'department_code' => 'CSCS',
                'name' => 'علوم الحاسب',
                'name_en' => 'Computer Science',
                'code' => 'CS',
            ],
            [
                'department_code' => 'CSCS',
                'name' => 'علوم البيانات',
                'name_en' => 'Data Science',
                'code' => 'DS',
            ],
            // قسم هندسة البرمجيات - جامعة صنعاء
            [
                'department_code' => 'SWE',
                'name' => 'هندسة البرمجيات',
                'name_en' => 'Software Engineering',
                'code' => 'SE',
            ],
            [
                'department_code' => 'SWE',
                'name' => 'تطوير تطبيقات الويب',
                'name_en' => 'Web Application Development',
                'code' => 'WAD',
            ],
            // قسم الشبكات - جامعة صنعاء
            [
                'department_code' => 'NET',
                'name' => 'شبكات الحاسب',
                'name_en' => 'Computer Networks',
                'code' => 'CN',
            ],
            [
                'department_code' => 'NET',
                'name' => 'أمن الشبكات',
                'name_en' => 'Network Security',
                'code' => 'NS',
            ],
            // قسم الأمن السيبراني - جامعة صنعاء
            [
                'department_code' => 'SEC',
                'name' => 'الأمن السيبراني',
                'name_en' => 'Cyber Security',
                'code' => 'CYB',
            ],
            [
                'department_code' => 'SEC',
                'name' => 'الأمن الرقمي',
                'name_en' => 'Digital Security',
                'code' => 'DSEC',
            ],
            // قسم الذكاء الاصطناعي - جامعة صنعاء
            [
                'department_code' => 'AI',
                'name' => 'الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence',
                'code' => 'ARTI',
            ],
            [
                'department_code' => 'AI',
                'name' => 'تعلم الآلة',
                'name_en' => 'Machine Learning',
                'code' => 'ML',
            ],
            // قسم علوم الحاسب - جامعة عدن
            [
                'department_code' => 'CSCA',
                'name' => 'علوم الحاسب',
                'name_en' => 'Computer Science',
                'code' => 'CSA',
            ],
            [
                'department_code' => 'CSCA',
                'name' => 'علوم البيانات',
                'name_en' => 'Data Science',
                'code' => 'DSA',
            ],
            // قسم هندسة البرمجيات - جامعة عدن
            [
                'department_code' => 'SWA',
                'name' => 'هندسة البرمجيات',
                'name_en' => 'Software Engineering',
                'code' => 'SEA',
            ],
            [
                'department_code' => 'SWA',
                'name' => 'تطوير تطبيقات الويب',
                'name_en' => 'Web Application Development',
                'code' => 'WADA',
            ],
            // قسم الشبكات - جامعة عدن
            [
                'department_code' => 'NTA',
                'name' => 'شبكات الحاسب',
                'name_en' => 'Computer Networks',
                'code' => 'CNA',
            ],
            [
                'department_code' => 'NTA',
                'name' => 'أمن الشبكات',
                'name_en' => 'Network Security',
                'code' => 'NSA',
            ],
            // قسم الأمن السيبراني - جامعة عدن
            [
                'department_code' => 'SCA',
                'name' => 'الأمن السيبراني',
                'name_en' => 'Cyber Security',
                'code' => 'CYBA',
            ],
            [
                'department_code' => 'SCA',
                'name' => 'الأمن الرقمي',
                'name_en' => 'Digital Security',
                'code' => 'DSECA',
            ],
            // قسم الذكاء الاصطناعي - جامعة عدن
            [
                'department_code' => 'AIA',
                'name' => 'الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence',
                'code' => 'ARTIA',
            ],
            [
                'department_code' => 'AIA',
                'name' => 'تعلم الآلة',
                'name_en' => 'Machine Learning',
                'code' => 'MLA',
            ],
            // قسم علوم الحاسب - جامعة ذمار
            [
                'department_code' => 'CSCD',
                'name' => 'علوم الحاسب',
                'name_en' => 'Computer Science',
                'code' => 'CSD',
            ],
            [
                'department_code' => 'CSCD',
                'name' => 'علوم البيانات',
                'name_en' => 'Data Science',
                'code' => 'DSD',
            ],
            // قسم هندسة البرمجيات - جامعة ذمار
            [
                'department_code' => 'SWD',
                'name' => 'هندسة البرمجيات',
                'name_en' => 'Software Engineering',
                'code' => 'SED',
            ],
            [
                'department_code' => 'SWD',
                'name' => 'تطوير تطبيقات الويب',
                'name_en' => 'Web Application Development',
                'code' => 'WADD',
            ],
            // قسم الشبكات - جامعة ذمار
            [
                'department_code' => 'NTD',
                'name' => 'شبكات الحاسب',
                'name_en' => 'Computer Networks',
                'code' => 'CND',
            ],
            [
                'department_code' => 'NTD',
                'name' => 'أمن الشبكات',
                'name_en' => 'Network Security',
                'code' => 'NSD',
            ],
            // قسم الأمن السيبراني - جامعة ذمار
            [
                'department_code' => 'SCD',
                'name' => 'الأمن السيبراني',
                'name_en' => 'Cyber Security',
                'code' => 'CYBD',
            ],
            [
                'department_code' => 'SCD',
                'name' => 'الأمن الرقمي',
                'name_en' => 'Digital Security',
                'code' => 'DSECD',
            ],
            // قسم الذكاء الاصطناعي - جامعة ذمار
            [
                'department_code' => 'AID',
                'name' => 'الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence',
                'code' => 'ARTID',
            ],
            [
                'department_code' => 'AID',
                'name' => 'تعلم الآلة',
                'name_en' => 'Machine Learning',
                'code' => 'MLD',
            ],
            // قسم علوم الحاسب - جامعة الحديدة
            [
                'department_code' => 'CSCH',
                'name' => 'علوم الحاسب',
                'name_en' => 'Computer Science',
                'code' => 'CSH',
            ],
            [
                'department_code' => 'CSCH',
                'name' => 'علوم البيانات',
                'name_en' => 'Data Science',
                'code' => 'DSH',
            ],
            // قسم هندسة البرمجيات - جامعة الحديدة
            [
                'department_code' => 'SWH',
                'name' => 'هندسة البرمجيات',
                'name_en' => 'Software Engineering',
                'code' => 'SEH',
            ],
            [
                'department_code' => 'SWH',
                'name' => 'تطوير تطبيقات الويب',
                'name_en' => 'Web Application Development',
                'code' => 'WADH',
            ],
            // قسم الشبكات - جامعة الحديدة
            [
                'department_code' => 'NTH',
                'name' => 'شبكات الحاسب',
                'name_en' => 'Computer Networks',
                'code' => 'CNH',
            ],
            [
                'department_code' => 'NTH',
                'name' => 'أمن الشبكات',
                'name_en' => 'Network Security',
                'code' => 'NSH',
            ],
            // قسم الأمن السيبراني - جامعة الحديدة
            [
                'department_code' => 'SCH',
                'name' => 'الأمن السيبراني',
                'name_en' => 'Cyber Security',
                'code' => 'CYBH',
            ],
            [
                'department_code' => 'SCH',
                'name' => 'الأمن الرقمي',
                'name_en' => 'Digital Security',
                'code' => 'DSECH',
            ],
            // قسم الذكاء الاصطناعي - جامعة الحديدة
            [
                'department_code' => 'AIH',
                'name' => 'الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence',
                'code' => 'ARTIH',
            ],
            [
                'department_code' => 'AIH',
                'name' => 'تعلم الآلة',
                'name_en' => 'Machine Learning',
                'code' => 'MLH',
            ],
            // قسم علوم الحاسب - جامعة إب
            [
                'department_code' => 'CSCI',
                'name' => 'علوم الحاسب',
                'name_en' => 'Computer Science',
                'code' => 'CSI',
            ],
            [
                'department_code' => 'CSCI',
                'name' => 'علوم البيانات',
                'name_en' => 'Data Science',
                'code' => 'DSI',
            ],
            // قسم هندسة البرمجيات - جامعة إب
            [
                'department_code' => 'SWI',
                'name' => 'هندسة البرمجيات',
                'name_en' => 'Software Engineering',
                'code' => 'SEI',
            ],
            [
                'department_code' => 'SWI',
                'name' => 'تطوير تطبيقات الويب',
                'name_en' => 'Web Application Development',
                'code' => 'WADI',
            ],
            // قسم الشبكات - جامعة إب
            [
                'department_code' => 'NTI',
                'name' => 'شبكات الحاسب',
                'name_en' => 'Computer Networks',
                'code' => 'CNI',
            ],
            [
                'department_code' => 'NTI',
                'name' => 'أمن الشبكات',
                'name_en' => 'Network Security',
                'code' => 'NSI',
            ],
            // قسم الأمن السيبراني - جامعة إب
            [
                'department_code' => 'SCI',
                'name' => 'الأمن السيبراني',
                'name_en' => 'Cyber Security',
                'code' => 'CYBI',
            ],
            [
                'department_code' => 'SCI',
                'name' => 'الأمن الرقمي',
                'name_en' => 'Digital Security',
                'code' => 'DSECI',
            ],
            // قسم الذكاء الاصطناعي - جامعة إب
            [
                'department_code' => 'AII',
                'name' => 'الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence',
                'code' => 'ARTII',
            ],
            [
                'department_code' => 'AII',
                'name' => 'تعلم الآلة',
                'name_en' => 'Machine Learning',
                'code' => 'MLI',
            ],
        ];

        foreach ($majors as $major) {
            $existing = DB::table('majors')
                ->where('code', $major['code'])
                ->first();

            if ($existing === null) {
                $departmentId = $departments[$major['department_code']] ?? null;

                if ($departmentId !== null) {
                    EloquentMajor::create([
                        'department_id' => $departmentId,
                        'name' => $major['name'],
                        'name_en' => $major['name_en'],
                        'code' => $major['code'],
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
