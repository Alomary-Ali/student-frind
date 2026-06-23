<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid|exists:users,id',
            'course_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:now',
        ];
    }
}
