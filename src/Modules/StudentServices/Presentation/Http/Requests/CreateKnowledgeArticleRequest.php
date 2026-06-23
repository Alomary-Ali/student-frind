<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateKnowledgeArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'tags' => ['nullable', 'array'],
            'status' => ['required', 'string', 'in:draft,published'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
