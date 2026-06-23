<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid',
            'message' => 'required|string|max:500',
            'trigger_at' => 'required|date|after:now',
            'type' => 'required|in:email,push,in_app',
            'linked_task_id' => 'nullable|uuid',
        ];
    }
}
