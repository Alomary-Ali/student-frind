<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AddCertificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'credential_url' => 'nullable|url|max:500',
            'verification_code' => 'nullable|string|max:100',
        ];
    }
}
