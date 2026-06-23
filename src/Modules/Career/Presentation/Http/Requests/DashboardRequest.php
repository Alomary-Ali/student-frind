<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gpa' => ['nullable', 'numeric', 'min:0', 'max:4'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
