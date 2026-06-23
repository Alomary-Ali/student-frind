<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'student_number' => ['required', 'string', 'max:50', 'unique:academic_students,student_number'],
            'institution_id' => ['nullable', 'uuid'],
            'university_id' => ['nullable', 'uuid'],
            'college_id' => ['nullable', 'uuid'],
            'department_id' => ['nullable', 'uuid'],
            'major_id' => ['nullable', 'uuid'],
            'level' => ['nullable', 'string', 'max:10'],
            'semester_gpa' => ['nullable', 'numeric', 'min:0', 'max:4'],
            'current_semester_id' => ['nullable', 'uuid'],
        ];
    }
}
