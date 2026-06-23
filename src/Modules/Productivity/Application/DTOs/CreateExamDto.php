<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class CreateExamDto
{
    public function __construct(
        public string $userId,
        public string $courseId,
        public string $title,
        public string $examType,
        public string $examDate,
        public string $location,
    ) {}

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            userId: $request->input('user_id') ?? auth()->id() ?? '',
            courseId: $request->input('course_id') ?? '',
            title: $request->input('title') ?? '',
            examType: $request->input('exam_type') ?? '',
            examDate: $request->input('exam_date') ?? '',
            location: $request->input('location') ?? '',
        );
    }
}
