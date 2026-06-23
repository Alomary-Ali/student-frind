<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class AssistantConversationDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public ?string $title,
        public string $status,
        public string $lastActivityAt,
        public string $createdAt,
    ) {}
}
