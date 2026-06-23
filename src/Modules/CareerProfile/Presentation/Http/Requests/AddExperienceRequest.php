<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AddExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_current' => 'boolean',
        ];
    }
}
