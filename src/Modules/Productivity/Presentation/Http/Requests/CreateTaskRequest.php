<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'linked_goal_id' => 'nullable|uuid',
        ];
    }
}
