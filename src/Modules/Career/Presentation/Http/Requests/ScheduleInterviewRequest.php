<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Career\Domain\Enums\InterviewType;

class ScheduleInterviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:' . implode(',', array_column(InterviewType::cases(), 'value'))],
            'scheduled_at' => ['required', 'date', 'after:now'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
