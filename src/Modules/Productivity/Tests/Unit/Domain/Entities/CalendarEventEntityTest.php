<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Entities\CalendarEvent;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use PHPUnit\Framework\TestCase;

final class CalendarEventEntityTest extends TestCase
{
    public function test_calendar_event_can_be_created(): void
    {
        $id = CalendarEventId::generate();
        $startsAt = new DateTimeImmutable('2026-07-01 10:00:00');
        $endsAt = new DateTimeImmutable('2026-07-01 12:00:00');

        $event = CalendarEvent::create(
            id: $id,
            userId: 'user-1',
            title: 'Study Session',
            description: 'Study for midterm',
            startsAt: $startsAt,
            endsAt: $endsAt,
            isAllDay: false,
        );

        $this->assertSame($id, $event->id());
        $this->assertSame('user-1', $event->userId());
        $this->assertSame('Study Session', $event->title());
        $this->assertSame('Study for midterm', $event->description());
        $this->assertSame($startsAt, $event->startsAt());
        $this->assertSame($endsAt, $event->endsAt());
        $this->assertFalse($event->isAllDay());
        $this->assertNull($event->linkedTaskId());
        $this->assertInstanceOf(DateTimeImmutable::class, $event->createdAt());
    }

    public function test_calendar_event_with_linked_task(): void
    {
        $taskId = TaskId::generate();
        $event = CalendarEvent::create(
            id: CalendarEventId::generate(),
            userId: 'user-1',
            title: 'Task Study',
            description: 'Linked to task',
            startsAt: new DateTimeImmutable('2026-07-01 10:00:00'),
            endsAt: new DateTimeImmutable('2026-07-01 12:00:00'),
            isAllDay: false,
            linkedTaskId: $taskId,
        );

        $this->assertSame($taskId, $event->linkedTaskId());
    }

    public function test_calendar_event_is_all_day(): void
    {
        $event = CalendarEvent::create(
            id: CalendarEventId::generate(),
            userId: 'user-1',
            title: 'All Day',
            description: 'All day event',
            startsAt: new DateTimeImmutable('2026-07-01 00:00:00'),
            endsAt: new DateTimeImmutable('2026-07-01 23:59:00'),
            isAllDay: true,
        );

        $this->assertTrue($event->isAllDay());
    }

    public function test_calendar_event_is_future(): void
    {
        $event = CalendarEvent::create(
            id: CalendarEventId::generate(),
            userId: 'user-1',
            title: 'Future',
            description: 'Future event',
            startsAt: new DateTimeImmutable('+1 year'),
            endsAt: new DateTimeImmutable('+1 year +1 hour'),
        );

        $this->assertTrue($event->isFuture());
        $this->assertFalse($event->isPast());
        $this->assertFalse($event->isOngoing());
    }

    public function test_calendar_event_is_past(): void
    {
        $event = CalendarEvent::create(
            id: CalendarEventId::generate(),
            userId: 'user-1',
            title: 'Past',
            description: 'Past event',
            startsAt: new DateTimeImmutable('-2 days'),
            endsAt: new DateTimeImmutable('-1 day'),
        );

        $this->assertTrue($event->isPast());
        $this->assertFalse($event->isFuture());
    }

    public function test_calendar_event_can_be_reconstituted(): void
    {
        $id = CalendarEventId::generate();
        $taskId = TaskId::generate();
        $startsAt = new DateTimeImmutable('2026-07-01 10:00:00');
        $endsAt = new DateTimeImmutable('2026-07-01 12:00:00');
        $createdAt = new DateTimeImmutable();

        $event = CalendarEvent::reconstitute(
            id: $id,
            userId: 'user-1',
            title: 'Reconstituted',
            description: 'From DB',
            startsAt: $startsAt,
            endsAt: $endsAt,
            isAllDay: true,
            linkedTaskId: $taskId,
            createdAt: $createdAt,
        );

        $this->assertSame($id, $event->id());
        $this->assertSame('Reconstituted', $event->title());
        $this->assertTrue($event->isAllDay());
        $this->assertSame($taskId, $event->linkedTaskId());
        $this->assertSame($createdAt, $event->createdAt());
    }
}
