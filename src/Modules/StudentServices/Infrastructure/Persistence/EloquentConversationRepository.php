<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Domain\Entities\AssistantConversation;
use Modules\StudentServices\Domain\Entities\AssistantMessage;
use Modules\StudentServices\Domain\Entities\AssistantSuggestion;
use Modules\StudentServices\Domain\Enums\ConversationStatus;
use Modules\StudentServices\Domain\Enums\MessageRole;
use Modules\StudentServices\Domain\ValueObjects\ConversationId;
use Modules\StudentServices\Domain\ValueObjects\MessageId;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentAssistantConversation;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentAssistantMessage;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentAssistantSuggestion;

final class EloquentConversationRepository implements ConversationRepositoryInterface
{
    public function findById(ConversationId $id): ?AssistantConversation
    {
        $model = EloquentAssistantConversation::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(string $studentId): array
    {
        return EloquentAssistantConversation::where('student_id', $studentId)
            ->orderBy('last_activity_at', 'desc')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findActiveByStudentId(string $studentId): ?AssistantConversation
    {
        $model = EloquentAssistantConversation::where('student_id', $studentId)
            ->where('status', 'active')
            ->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(AssistantConversation $conversation): void
    {
        $model = EloquentAssistantConversation::find($conversation->id()->value());

        if ($model === null) {
            $model = new EloquentAssistantConversation;
            $model->id = $conversation->id()->value();
        }

        $model->student_id = $conversation->studentId();
        $model->title = $conversation->title();
        $model->status = $conversation->status()->value;
        $model->context_data = $conversation->contextData();
        $model->last_activity_at = $conversation->lastActivityAt()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function saveMessage(AssistantMessage $message): void
    {
        $model = EloquentAssistantMessage::find($message->id()->value());

        if ($model === null) {
            $model = new EloquentAssistantMessage;
            $model->id = $message->id()->value();
        }

        $model->conversation_id = $message->conversationId();
        $model->role = $message->role()->value;
        $model->content = $message->content();
        $model->metadata = $message->metadata();
        $model->created_at = $message->createdAt()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function getMessages(ConversationId $conversationId): array
    {
        return EloquentAssistantMessage::where('conversation_id', $conversationId->value())
            ->orderBy('created_at')
            ->get()
            ->map(fn ($model) => $this->toMessageEntity($model))
            ->toArray();
    }

    public function getSuggestions(string $conversationId, string $messageId): array
    {
        return EloquentAssistantSuggestion::where('conversation_id', $conversationId)
            ->where('message_id', $messageId)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($model) => $this->toSuggestionEntity($model))
            ->toArray();
    }

    private function toEntity(EloquentAssistantConversation $model): AssistantConversation
    {
        return AssistantConversation::reconstitute(
            id: ConversationId::of($model->id),
            studentId: $model->student_id,
            title: $model->title,
            status: ConversationStatus::from($model->status),
            contextData: $model->context_data ?? [],
            lastActivityAt: new DateTimeImmutable($model->last_activity_at->format('Y-m-d H:i:s')),
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }

    private function toMessageEntity(EloquentAssistantMessage $model): AssistantMessage
    {
        return AssistantMessage::reconstitute(
            id: MessageId::of($model->id),
            conversationId: $model->conversation_id,
            role: MessageRole::from($model->role),
            content: $model->content,
            metadata: $model->metadata ?? [],
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
        );
    }

    private function toSuggestionEntity(EloquentAssistantSuggestion $model): AssistantSuggestion
    {
        return AssistantSuggestion::reconstitute(
            id: $model->id,
            conversationId: $model->conversation_id,
            messageId: $model->message_id,
            suggestionType: $model->suggestion_type,
            title: $model->title,
            actionUrl: $model->action_url,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
        );
    }
}
