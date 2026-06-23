<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;

final class GenerateResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template' => ['required', new Enum(ResumeTemplate::class)],
        ];
    }
}
