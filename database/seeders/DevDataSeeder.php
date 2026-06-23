<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DevDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // AuthorizationSeeder::class, // Already run separately
            UniversitySeeder::class,
            CollegeSeeder::class,
            DepartmentSeeder::class,
            MajorSeeder::class,
            SemesterSeeder::class,
            TestUsersSeeder::class,
            CourseSeeder::class,
            StudentDemoSeeder::class,
        ]);
    }
}
