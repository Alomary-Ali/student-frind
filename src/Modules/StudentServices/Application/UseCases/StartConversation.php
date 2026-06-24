<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Domain\Entities\AssistantConversation;
use Modules\StudentServices\Domain\ValueObjects\ConversationId;

final readonly class StartConversation
{
    public function __construct(
        private ConversationRepositoryInterface $conversations,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $studentId, ?string $title = null): array
    {
        $conversation = AssistantConversation::create(
            id: ConversationId::generate(),
            studentId: $studentId,
            title: $title,
        );

        $this->conversations->save($conversation);
        $this->events->dispatch($conversation->releaseEvents());

        return [
            'id' => $conversation->id()->value(),
            'student_id' => $conversation->studentId(),
            'title' => $conversation->title(),
            'status' => $conversation->status()->value,
            'created_at' => $conversation->createdAt()->format('c'),
        ];
    }
}
