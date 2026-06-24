<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;

trait WithAuthenticatedStudent
{
    protected function createAndAuthenticateStudent(string $role = 'student', string $userId = '550e8400-e29b-41d4-a716-446655440000'): EloquentUser
    {
        $user = EloquentUser::create([
            'id' => $userId,
            'email' => $role . '@test.com',
            'first_name' => 'Test',
            'last_name' => ucfirst($role),
            'password_hash' => Hash::make('password'),
            'role' => $role,
            'status' => 'active',
            'academic_id' => null,
        ]);

        EloquentStudent::create([
            'id' => 'student-' . $userId,
            'user_id' => $user->id,
            'student_number' => 'S' . substr($userId, 0, 8),
            'academic_status' => 'active',
            'academic_standing' => 'good',
            'cumulative_gpa' => 3.5,
            'semester_gpa' => 3.7,
            'current_semester_id' => null,
            'institution_id' => null,
            'college_id' => null,
            'department_id' => null,
            'program_id' => null,
            'classification' => 'regular',
            'admission_date' => now(),
            'expected_graduation' => null,
        ]);

        Sanctum::actingAs($user);

        return $user;
    }
}
