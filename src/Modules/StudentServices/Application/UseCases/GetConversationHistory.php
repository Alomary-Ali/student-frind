<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\ConversationId;

final readonly class GetConversationHistory
{
    public function __construct(
        private ConversationRepositoryInterface $conversations,
    ) {}

    public function execute(string $conversationId): ?array
    {
        $id = ConversationId::fromString($conversationId);
        $conversation = $this->conversations->findById($id);

        if ($conversation === null) {
            return null;
        }

        $messages = $this->conversations->getMessages($id);

        return [
            'conversation' => [
                'id' => $conversation->id()->value(),
                'student_id' => $conversation->studentId(),
                'title' => $conversation->title(),
                'status' => $conversation->status()->value,
                'created_at' => $conversation->createdAt()->format('c'),
                'last_activity_at' => $conversation->lastActivityAt()->format('c'),
            ],
            'messages' => array_map(fn ($m) => [
                'id' => $m->id()->value(),
                'role' => $m->role()->value,
                'content' => $m->content(),
                'created_at' => $m->createdAt()->format('c'),
            ], $messages),
        ];
    }
}
