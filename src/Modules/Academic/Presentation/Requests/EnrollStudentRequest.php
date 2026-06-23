<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class EnrollStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        // Only admins, advisors, or the student themselves can enroll
        return in_array($user->role, ['admin', 'advisor'])
            || $user->academic_id === $this->input('student_id');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'uuid', 'exists:academic_students,id'],
            'course_id' => ['required', 'uuid', 'exists:academic_courses,id'],
            'semester_id' => ['required', 'uuid', 'exists:academic_semesters,id'],
        ];
    }
}
