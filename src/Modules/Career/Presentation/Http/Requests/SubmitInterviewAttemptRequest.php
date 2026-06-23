<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitInterviewAttemptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'string'],
            'answers.*.answer' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
