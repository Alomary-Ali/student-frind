<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class AddPortfolioItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'project_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'technologies' => 'nullable|array',
        ];
    }
}
