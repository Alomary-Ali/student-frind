<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts;

use Modules\StudentServices\Domain\Entities\AssistantConversation;
use Modules\StudentServices\Domain\Entities\AssistantMessage;
use Modules\StudentServices\Domain\ValueObjects\ConversationId;

interface ConversationRepositoryInterface
{
    public function findById(ConversationId $id): ?AssistantConversation;

    public function findByStudentId(string $studentId): array;

    public function findActiveByStudentId(string $studentId): ?AssistantConversation;

    public function save(AssistantConversation $conversation): void;

    public function saveMessage(AssistantMessage $message): void;

    public function getMessages(ConversationId $conversationId): array;

    public function getSuggestions(string $conversationId, string $messageId): array;
}
