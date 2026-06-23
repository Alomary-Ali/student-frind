<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateCalendarEventRequest extends FormRequest
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
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_all_day' => 'boolean',
            'linked_task_id' => 'nullable|uuid',
        ];
    }
}
