<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class AssistantMessageDto
{
    public function __construct(
        public string $id,
        public string $conversationId,
        public string $role,
        public string $content,
        public string $createdAt,
    ) {}
}
