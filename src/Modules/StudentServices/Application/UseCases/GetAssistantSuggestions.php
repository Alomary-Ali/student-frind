<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;

final readonly class GetAssistantSuggestions
{
    public function __construct(
        private ConversationRepositoryInterface $conversations,
    ) {}

    public function execute(string $conversationId, string $messageId): array
    {
        return $this->conversations->getSuggestions($conversationId, $messageId);
    }
}
