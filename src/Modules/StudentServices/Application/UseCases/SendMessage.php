<?php
declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface;
use Modules\StudentServices\Domain\Entities\AssistantMessage;
use Modules\StudentServices\Domain\Enums\MessageRole;
use Modules\StudentServices\Domain\Events\MessageAdded;
use Modules\StudentServices\Domain\ValueObjects\ConversationId;
use Modules\StudentServices\Domain\ValueObjects\MessageId;

final readonly class SendMessage
{
    public function __construct(
        private ConversationRepositoryInterface $conversations,
        private AiAssistantGatewayInterface $ai,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $conversationId, string $studentId, string $content): ?array
    {
        $id = ConversationId::fromString($conversationId);
        $conversation = $this->conversations->findById($id);

        if ($conversation === null) {
            return null;
        }

        $conversation->touch();

        $userMessage = AssistantMessage::create(
            id: MessageId::generate(),
            conversationId: $conversationId,
            role: MessageRole::USER,
            content: $content,
        );

        $this->conversations->save($conversation);
        $this->conversations->saveMessage($userMessage);

        $aiResponse = $this->ai->ask($conversationId, $studentId, $content);

        $assistantContent = $aiResponse['content'] ?? '';

        $assistantMessage = AssistantMessage::create(
            id: MessageId::generate(),
            conversationId: $conversationId,
            role: MessageRole::ASSISTANT,
            content: $assistantContent,
        );

        $this->conversations->saveMessage($assistantMessage);

        $this->events->dispatch([
            new MessageAdded(
                messageId: $userMessage->id()->value(),
                conversationId: $conversationId,
                role: MessageRole::USER->value,
                content: $content,
                occurredAt: $userMessage->createdAt(),
            ),
            new MessageAdded(
                messageId: $assistantMessage->id()->value(),
                conversationId: $conversationId,
                role: MessageRole::ASSISTANT->value,
                content: $assistantContent,
                occurredAt: $assistantMessage->createdAt(),
            ),
        ]);

        $suggestions = $this->ai->generateSuggestions($conversationId, $assistantMessage->id()->value());

        return [
            'user_message' => [
                'id' => $userMessage->id()->value(),
                'content' => $userMessage->content(),
                'role' => $userMessage->role()->value,
                'created_at' => $userMessage->createdAt()->format('c'),
            ],
            'assistant_reply' => [
                'id' => $assistantMessage->id()->value(),
                'content' => $assistantMessage->content(),
                'role' => $assistantMessage->role()->value,
                'created_at' => $assistantMessage->createdAt()->format('c'),
            ],
            'suggestions' => $suggestions,
        ];
    }
}
