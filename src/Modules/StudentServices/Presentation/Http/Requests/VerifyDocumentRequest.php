<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyDocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required_without:verification_code', 'string', 'max:64'],
            'verification_code' => ['required_without:code', 'string', 'max:64'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
