<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AuthorizationSeeder::class,
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
