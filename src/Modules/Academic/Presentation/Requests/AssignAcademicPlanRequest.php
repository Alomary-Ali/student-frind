<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AssignAcademicPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'uuid', 'exists:academic_students,id'],
            'curriculum_id' => ['required', 'uuid', 'exists:academic_curricula,id'],
            'institution_id' => ['nullable', 'uuid'],
            'estimated_graduation_date' => ['nullable', 'date'],
        ];
    }
}
