<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AnalyzeSkillGapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_role' => 'required|string|max:100',
        ];
    }
}
