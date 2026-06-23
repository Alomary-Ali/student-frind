<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class AiAssistantResponseDto
{
    public function __construct(
        public string $reply,
        public array $suggestions,
        public int $tokensUsed,
    ) {}
}
