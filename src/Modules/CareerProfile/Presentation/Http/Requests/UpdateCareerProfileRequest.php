<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCareerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'major' => 'required|string|max:255',
            'summary' => 'nullable|string|max:2000',
            'interests' => 'nullable|array',
            'languages' => 'nullable|array',
        ];
    }
}
