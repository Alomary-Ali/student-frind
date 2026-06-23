<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'academic_id' => ['required', 'string', 'regex:/^\d{8}$/'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'academic_id.required' => 'الرقم الأكاديمي مطلوب',
            'academic_id.regex' => 'الرقم الأكاديمي يجب أن يكون 8 أرقام',
            'password.required' => 'كلمة المرور مطلوبة',
        ];
    }
}
