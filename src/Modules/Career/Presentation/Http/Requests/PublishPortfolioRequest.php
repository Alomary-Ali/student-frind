<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Career\Domain\Enums\PortfolioTheme;

class PublishPortfolioRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'slug' => ['nullable', 'string', 'regex:/^[a-z0-9][a-z0-9-]{1,98}[a-z0-9]$/', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'theme' => ['nullable', 'string', 'in:' . implode(',', array_column(PortfolioTheme::cases(), 'value'))],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
