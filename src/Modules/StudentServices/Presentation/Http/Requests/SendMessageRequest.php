<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'uuid'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
