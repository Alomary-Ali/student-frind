<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class CreateAssignmentDto
{
    public function __construct(
        public string $userId,
        public string $courseId,
        public string $title,
        public string $description,
        public string $dueDate,
    ) {}

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            userId: $request->input('user_id') ?? auth()->id() ?? '',
            courseId: $request->input('course_id') ?? '',
            title: $request->input('title') ?? '',
            description: $request->input('description') ?? '',
            dueDate: $request->input('due_date') ?? '',
        );
    }
}
