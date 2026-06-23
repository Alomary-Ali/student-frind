<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|uuid|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:start_date',
        ];
    }
}
