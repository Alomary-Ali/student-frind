<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Domain\Enums\Role;
use Modules\Shared\Domain\Enums\UserStatus;

final class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Use existing users from database
        $users = [
            // Existing admin
            [
                'academic_id' => '20210003',
                'email' => 'admin@rafiq.test',
                'first_name' => 'Admin',
                'last_name' => 'Rafiq',
                'password' => 'Admin@1234',
                'role' => Role::ADMIN,
                'status' => UserStatus::Active,
            ],
            // Existing student
            [
                'academic_id' => '20210001',
                'email' => 'student@rafiq.test',
                'first_name' => 'محمد',
                'last_name' => 'الأحمدي',
                'password' => 'Student@1234',
                'role' => Role::STUDENT,
                'status' => UserStatus::Active,
                'level' => '1',
                'gpa' => 0.0,
            ],
            // Existing advisor
            [
                'academic_id' => '20210002',
                'email' => 'advisor@rafiq.test',
                'first_name' => 'سارة',
                'last_name' => 'العمري',
                'password' => 'Advisor@1234',
                'role' => Role::ADVISOR,
                'status' => UserStatus::Active,
            ],
        ];

        foreach ($users as $userData) {
            $existing = DB::table('users')
                ->where('academic_id', $userData['academic_id'])
                ->first();

            if ($existing === null) {
                $userId = (string) \Illuminate\Support\Str::uuid();
                DB::table('users')->insert([
                    'id' => $userId,
                    'academic_id' => $userData['academic_id'],
                    'email' => $userData['email'],
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'password_hash' => Hash::make($userData['password']),
                    'role' => $userData['role']->value,
                    'status' => $userData['status']->value,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $existing = DB::table('users')->where('id', $userId)->first();
            } else {
                // Update existing user
                DB::table('users')
                    ->where('academic_id', $userData['academic_id'])
                    ->update([
                        'password_hash' => Hash::make($userData['password']),
                        'role' => $userData['role']->value,
                        'status' => $userData['status']->value,
                        'email_verified_at' => now(),
                        'failed_login_attempts' => 0,
                        'locked_until' => null,
                        'updated_at' => now(),
                    ]);
            }

            // Assign role to user
            $role = DB::table('roles')
                ->where('name', $userData['role']->value)
                ->first();

            if ($role !== null) {
                $userRole = DB::table('user_roles')
                    ->where('user_id', $existing->id)
                    ->first();

                if ($userRole === null) {
                    DB::table('user_roles')->insert([
                        'user_id' => $existing->id,
                        'role_id' => $role->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // If student, create or update student profile
            if ($userData['role'] === Role::STUDENT) {
                $university = DB::table('universities')
                    ->where('code', 'SANA')
                    ->first();

                $college = DB::table('colleges')
                    ->where('code', 'CSIS')
                    ->first();

                $department = DB::table('departments')
                    ->where('code', 'CSCS')
                    ->first();

                $major = DB::table('majors')
                    ->where('code', 'CS')
                    ->first();

                if ($university !== null && $college !== null && $department !== null && $major !== null) {
                    $student = DB::table('academic_students')
                        ->where('user_id', $existing->id)
                        ->first();

                    if ($student === null) {
                        DB::table('academic_students')->insert([
                            'id' => (string) \Illuminate\Support\Str::uuid(),
                            'user_id' => $existing->id,
                            'student_number' => $userData['academic_id'],
                            'academic_status' => 'active',
                            'academic_standing' => 'good_standing',
                            'cumulative_gpa' => $userData['gpa'],
                            'semester_gpa' => 0.0,
                            'current_semester_id' => null,
                            'institution_id' => null,
                            'university_id' => $university->id,
                            'college_id' => $college->id,
                            'department_id' => $department->id,
                            'major_id' => $major->id,
                            'level' => $userData['level'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
