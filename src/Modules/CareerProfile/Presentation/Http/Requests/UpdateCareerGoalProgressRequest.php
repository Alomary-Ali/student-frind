<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCareerGoalProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'progress' => 'required|integer|min:0|max:100',
        ];
    }
}
