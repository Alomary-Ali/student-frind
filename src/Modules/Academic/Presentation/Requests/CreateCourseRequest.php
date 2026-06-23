<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:academic_courses,code'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'credit_hours' => ['required', 'integer', 'min:1', 'max:10'],
            'institution_id' => ['nullable', 'uuid'],
        ];
    }
}
