<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Entities\AssistantConversation;
use Modules\StudentServices\Domain\Enums\ConversationStatus;
use Modules\StudentServices\Domain\Events\ConversationStarted;
use Modules\StudentServices\Domain\ValueObjects\ConversationId;
use PHPUnit\Framework\TestCase;

final class AssistantConversationEntityTest extends TestCase
{
    public function test_create_returns_conversation_with_active_status(): void
    {
        $id = ConversationId::generate();
        $conversation = AssistantConversation::create($id, 'student-1', 'محادثة جديدة');

        $this->assertSame($id, $conversation->id());
        $this->assertSame('student-1', $conversation->studentId());
        $this->assertSame('محادثة جديدة', $conversation->title());
        $this->assertSame(ConversationStatus::ACTIVE, $conversation->status());
        $this->assertEmpty($conversation->contextData());
    }

    public function test_create_with_context_data(): void
    {
        $conversation = AssistantConversation::create(
            ConversationId::generate(),
            'student-1',
            null,
            ['topic' => 'registration', 'language' => 'ar'],
        );

        $this->assertSame(['topic' => 'registration', 'language' => 'ar'], $conversation->contextData());
    }

    public function test_create_dispatches_started_event(): void
    {
        $id = ConversationId::generate();
        $conversation = AssistantConversation::create($id, 'student-1');

        $events = $conversation->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ConversationStarted::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->conversationId);
        $this->assertSame('student-1', $events[0]->studentId);
    }

    public function test_close_changes_status(): void
    {
        $conversation = AssistantConversation::create(ConversationId::generate(), 'student-1');

        $conversation->close();

        $this->assertSame(ConversationStatus::CLOSED, $conversation->status());
    }

    public function test_archive_changes_status(): void
    {
        $conversation = AssistantConversation::create(ConversationId::generate(), 'student-1');
        $conversation->close();

        $conversation->archive();

        $this->assertSame(ConversationStatus::ARCHIVED, $conversation->status());
    }

    public function test_update_context_merges_data(): void
    {
        $conversation = AssistantConversation::create(
            ConversationId::generate(),
            'student-1',
            null,
            ['topic' => 'registration'],
        );

        $conversation->updateContext(['language' => 'ar', 'step' => 1]);

        $this->assertSame(['topic' => 'registration', 'language' => 'ar', 'step' => 1], $conversation->contextData());
    }

    public function test_touch_updates_last_activity(): void
    {
        $conversation = AssistantConversation::create(ConversationId::generate(), 'student-1');
        $originalActivity = $conversation->lastActivityAt();

        sleep(1);
        $conversation->touch();

        $this->assertGreaterThan($originalActivity, $conversation->lastActivityAt());
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = ConversationId::generate();
        $now = new DateTimeImmutable;

        $conversation = AssistantConversation::reconstitute(
            id: $id,
            studentId: 'student-1',
            title: 'عنوان',
            status: ConversationStatus::CLOSED,
            contextData: ['key' => 'value'],
            lastActivityAt: $now,
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id->value(), $conversation->id()->value());
        $this->assertSame(ConversationStatus::CLOSED, $conversation->status());
        $this->assertSame(['key' => 'value'], $conversation->contextData());
    }

    public function test_release_events_clears_events(): void
    {
        $conversation = AssistantConversation::create(ConversationId::generate(), 'student-1');

        $this->assertCount(1, $conversation->releaseEvents());
        $this->assertCount(0, $conversation->releaseEvents());
    }
}
