<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Academic\Domain\Enums\GradeLetter;

final class RecordGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $grades = array_column(GradeLetter::cases(), 'value');

        return [
            'enrollment_id' => ['required', 'uuid', 'exists:academic_enrollments,id'],
            'grade' => ['required', 'string', Rule::in($grades)],
        ];
    }
}
