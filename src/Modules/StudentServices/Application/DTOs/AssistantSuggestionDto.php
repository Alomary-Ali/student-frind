<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class AssistantSuggestionDto
{
    public function __construct(
        public string $id,
        public string $conversationId,
        public string $messageId,
        public string $suggestionType,
        public string $title,
        public ?string $actionUrl,
    ) {}
}
