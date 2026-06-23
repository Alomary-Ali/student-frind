<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateGoalRequest extends FormRequest
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
            'target_date' => 'required|date|after:today',
            'priority' => 'required|in:low,medium,high,urgent',
        ];
    }
}
