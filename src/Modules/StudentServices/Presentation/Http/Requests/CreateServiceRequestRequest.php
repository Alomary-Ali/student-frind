<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
