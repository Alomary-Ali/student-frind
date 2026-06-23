<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UnlockAchievementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'completed_courses_count' => 'nullable|integer|min:0',
            'completed_tasks_count' => 'nullable|integer|min:0',
            'completed_goals_count' => 'nullable|integer|min:0',
        ];
    }
}
