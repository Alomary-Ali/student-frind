<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\ValueObjects;

use Modules\Productivity\Domain\Exceptions\InvalidAssignmentIdException;
use Modules\Productivity\Domain\Exceptions\InvalidCalendarEventIdException;
use Modules\Productivity\Domain\Exceptions\InvalidExamIdException;
use Modules\Productivity\Domain\Exceptions\InvalidGoalIdException;
use Modules\Productivity\Domain\Exceptions\InvalidProductivitySnapshotIdException;
use Modules\Productivity\Domain\Exceptions\InvalidProjectIdException;
use Modules\Productivity\Domain\Exceptions\InvalidReminderIdException;
use Modules\Productivity\Domain\Exceptions\InvalidTaskIdException;
use Modules\Productivity\Domain\ValueObjects\AssignmentId;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;
use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Productivity\Domain\ValueObjects\GoalId;
use Modules\Productivity\Domain\ValueObjects\ProductivitySnapshotId;
use Modules\Productivity\Domain\ValueObjects\ProjectId;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Productivity\Domain\ValueObjects\TaskId;
use PHPUnit\Framework\TestCase;

final class ProductivityValueObjectsTest extends TestCase
{
    public static function validUuid(): string
    {
        return '550e8400-e29b-41d4-a716-446655440000';
    }

    public static function invalidUuid(): string
    {
        return 'not-a-uuid';
    }

    public function test_goal_id_can_be_generated(): void
    {
        $id = GoalId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_goal_id_from_valid_string(): void
    {
        $id = GoalId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_goal_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidGoalIdException::class);
        GoalId::fromString(self::invalidUuid());
    }

    public function test_goal_id_equals(): void
    {
        $a = GoalId::fromString(self::validUuid());
        $b = GoalId::fromString(self::validUuid());
        $c = GoalId::generate();
        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_task_id_can_be_generated(): void
    {
        $id = TaskId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_task_id_from_valid_string(): void
    {
        $id = TaskId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_task_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidTaskIdException::class);
        TaskId::fromString(self::invalidUuid());
    }

    public function test_task_id_equals(): void
    {
        $a = TaskId::fromString(self::validUuid());
        $b = TaskId::fromString(self::validUuid());
        $this->assertTrue($a->equals($b));
    }

    public function test_project_id_can_be_generated(): void
    {
        $id = ProjectId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_project_id_from_valid_string(): void
    {
        $id = ProjectId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_project_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidProjectIdException::class);
        ProjectId::fromString(self::invalidUuid());
    }

    public function test_project_id_equals(): void
    {
        $a = ProjectId::fromString(self::validUuid());
        $b = ProjectId::fromString(self::validUuid());
        $this->assertTrue($a->equals($b));
    }

    public function test_project_id_to_string(): void
    {
        $id = ProjectId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), (string) $id);
    }

    public function test_exam_id_can_be_generated(): void
    {
        $id = ExamId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_exam_id_from_valid_string(): void
    {
        $id = ExamId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_exam_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidExamIdException::class);
        ExamId::fromString(self::invalidUuid());
    }

    public function test_exam_id_equals(): void
    {
        $a = ExamId::fromString(self::validUuid());
        $b = ExamId::fromString(self::validUuid());
        $this->assertTrue($a->equals($b));
    }

    public function test_assignment_id_can_be_generated(): void
    {
        $id = AssignmentId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_assignment_id_from_valid_string(): void
    {
        $id = AssignmentId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_assignment_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidAssignmentIdException::class);
        AssignmentId::fromString(self::invalidUuid());
    }

    public function test_assignment_id_equals(): void
    {
        $a = AssignmentId::fromString(self::validUuid());
        $b = AssignmentId::fromString(self::validUuid());
        $this->assertTrue($a->equals($b));
    }

    public function test_calendar_event_id_can_be_generated(): void
    {
        $id = CalendarEventId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_calendar_event_id_from_valid_string(): void
    {
        $id = CalendarEventId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_calendar_event_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidCalendarEventIdException::class);
        CalendarEventId::fromString(self::invalidUuid());
    }

    public function test_calendar_event_id_equals(): void
    {
        $a = CalendarEventId::fromString(self::validUuid());
        $b = CalendarEventId::fromString(self::validUuid());
        $this->assertTrue($a->equals($b));
    }

    public function test_reminder_id_can_be_generated(): void
    {
        $id = ReminderId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_reminder_id_from_valid_string(): void
    {
        $id = ReminderId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_reminder_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidReminderIdException::class);
        ReminderId::fromString(self::invalidUuid());
    }

    public function test_reminder_id_equals(): void
    {
        $a = ReminderId::fromString(self::validUuid());
        $b = ReminderId::fromString(self::validUuid());
        $this->assertTrue($a->equals($b));
    }

    public function test_snapshot_id_can_be_generated(): void
    {
        $id = ProductivitySnapshotId::generate();
        $this->assertMatchesRegularExpression('/^[0-9a-f\-]{36}$/', $id->value());
    }

    public function test_snapshot_id_from_valid_string(): void
    {
        $id = ProductivitySnapshotId::fromString(self::validUuid());
        $this->assertSame(self::validUuid(), $id->value());
    }

    public function test_snapshot_id_from_invalid_string_throws(): void
    {
        $this->expectException(InvalidProductivitySnapshotIdException::class);
        ProductivitySnapshotId::fromString(self::invalidUuid());
    }

    public function test_snapshot_id_equals(): void
    {
        $a = ProductivitySnapshotId::fromString(self::validUuid());
        $b = ProductivitySnapshotId::fromString(self::validUuid());
        $this->assertTrue($a->equals($b));
    }
}
