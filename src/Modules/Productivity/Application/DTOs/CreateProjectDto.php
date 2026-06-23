<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\DTOs;

final readonly class CreateProjectDto
{
    public function __construct(
        public string $userId,
        public string $title,
        public string $description,
        public string $startDate,
        public string $dueDate,
    ) {}

    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        return new self(
            userId: $request->input('user_id') ?? auth()->id() ?? '',
            title: $request->input('title') ?? '',
            description: $request->input('description') ?? '',
            startDate: $request->input('start_date') ?? now()->toDateTimeString(),
            dueDate: $request->input('due_date') ?? '',
        );
    }
}
