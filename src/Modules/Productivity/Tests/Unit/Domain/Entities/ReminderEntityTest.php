<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\Enums\ReminderStatus;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\Events\ReminderCreated;
use Modules\Productivity\Domain\Events\ReminderTriggered;
use Modules\Productivity\Domain\Exceptions\ReminderAlreadyTriggeredException;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use PHPUnit\Framework\TestCase;

final class ReminderEntityTest extends TestCase
{
    public function test_reminder_can_be_created(): void
    {
        $id = ReminderId::generate();
        $triggerAt = new DateTimeImmutable('2026-07-01 09:00:00');

        $reminder = Reminder::create(
            id: $id,
            userId: 'user-1',
            message: 'Task due tomorrow',
            triggerAt: $triggerAt,
            type: ReminderType::InApp,
        );

        $this->assertSame($id, $reminder->id());
        $this->assertSame('user-1', $reminder->userId());
        $this->assertSame('Task due tomorrow', $reminder->message());
        $this->assertSame($triggerAt, $reminder->triggerAt());
        $this->assertSame(ReminderType::InApp, $reminder->type());
        $this->assertSame(ReminderStatus::Pending, $reminder->status());
        $this->assertNull($reminder->linkedTaskId());
        $this->assertNull($reminder->triggeredAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $reminder->createdAt());
    }

    public function test_reminder_emits_created_event(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Test',
            triggerAt: new DateTimeImmutable('2026-07-01 09:00:00'),
            type: ReminderType::Push,
        );

        $events = $reminder->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ReminderCreated::class, $events[0]);
    }

    public function test_reminder_with_linked_task(): void
    {
        $taskId = TaskId::generate();
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Task reminder',
            triggerAt: new DateTimeImmutable('2026-07-01 09:00:00'),
            type: ReminderType::Email,
            linkedTaskId: $taskId,
        );

        $this->assertSame($taskId, $reminder->linkedTaskId());
    }

    public function test_reminder_can_be_triggered(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Test',
            triggerAt: new DateTimeImmutable('2026-07-01 09:00:00'),
            type: ReminderType::InApp,
        );

        $reminder->releaseEvents();
        $reminder->trigger();

        $this->assertTrue($reminder->status()->isTriggered());

        $events = $reminder->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ReminderTriggered::class, $events[0]);
    }

    public function test_reminder_cannot_be_triggered_twice(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Test',
            triggerAt: new DateTimeImmutable('2026-07-01 09:00:00'),
            type: ReminderType::InApp,
        );

        $reminder->releaseEvents();
        $reminder->trigger();

        $this->expectException(ReminderAlreadyTriggeredException::class);
        $reminder->trigger();
    }

    public function test_reminder_can_be_dismissed(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Test',
            triggerAt: new DateTimeImmutable('2026-07-01 09:00:00'),
            type: ReminderType::InApp,
        );

        $reminder->dismiss();

        $this->assertTrue($reminder->status()->isDismissed());
    }

    public function test_reminder_is_due_when_trigger_at_passed(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Test',
            triggerAt: new DateTimeImmutable('-1 day'),
            type: ReminderType::InApp,
        );

        $this->assertTrue($reminder->isDue());
    }

    public function test_reminder_is_not_due_when_trigger_at_future(): void
    {
        $reminder = Reminder::create(
            id: ReminderId::generate(),
            userId: 'user-1',
            message: 'Test',
            triggerAt: new DateTimeImmutable('+1 year'),
            type: ReminderType::InApp,
        );

        $this->assertFalse($reminder->isDue());
    }

    public function test_reminder_can_be_reconstituted(): void
    {
        $id = ReminderId::generate();
        $taskId = TaskId::generate();
        $triggerAt = new DateTimeImmutable('2026-07-01 09:00:00');
        $createdAt = new DateTimeImmutable;
        $triggeredAt = new DateTimeImmutable;

        $reminder = Reminder::reconstitute(
            id: $id,
            userId: 'user-1',
            message: 'Reconstituted',
            triggerAt: $triggerAt,
            type: ReminderType::Push,
            linkedTaskId: $taskId,
            status: ReminderStatus::Triggered,
            createdAt: $createdAt,
            triggeredAt: $triggeredAt,
        );

        $this->assertSame($id, $reminder->id());
        $this->assertSame('Reconstituted', $reminder->message());
        $this->assertSame(ReminderType::Push, $reminder->type());
        $this->assertSame(ReminderStatus::Triggered, $reminder->status());
        $this->assertSame($taskId, $reminder->linkedTaskId());
        $this->assertSame($createdAt, $reminder->createdAt());
        $this->assertSame($triggeredAt, $reminder->triggeredAt());
    }
}
