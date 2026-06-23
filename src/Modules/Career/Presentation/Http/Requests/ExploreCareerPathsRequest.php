<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExploreCareerPathsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'target_role' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
