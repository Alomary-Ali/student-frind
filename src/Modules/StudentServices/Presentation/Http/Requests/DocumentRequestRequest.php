<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string', 'in:certificate,transcript,statement,official_letter,id_card'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
