<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts\Gateways;

interface AiAssistantGatewayInterface
{
    public function ask(string $conversationId, string $studentId, string $message, array $context = []): array;

    public function generateSuggestions(string $conversationId, string $messageId, array $context = []): array;

    public function searchKnowledge(string $query): array;
}
