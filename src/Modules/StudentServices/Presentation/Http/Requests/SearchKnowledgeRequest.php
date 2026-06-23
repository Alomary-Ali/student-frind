<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchKnowledgeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'max:200'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
