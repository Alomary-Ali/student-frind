<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCareerGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'target_date' => 'required|date|after_or_equal:today',
        ];
    }
}
