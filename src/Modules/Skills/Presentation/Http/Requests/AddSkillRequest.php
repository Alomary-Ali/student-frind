<?php

declare(strict_types=1);

namespace Modules\Skills\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;

final class AddSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'category' => ['required', new Enum(SkillCategory::class)],
            'level' => ['required', new Enum(SkillLevel::class)],
            'years_of_experience' => 'nullable|integer|min:0|max:50',
        ];
    }
}
