<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Modules\Productivity\Domain\Entities\Assignment;
use Modules\Productivity\Domain\Enums\AssignmentStatus;
use Modules\Productivity\Domain\ValueObjects\AssignmentId;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class AssignmentTest extends TestCase
{
    public function test_can_create_assignment(): void
    {
        $assignment = Assignment::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('+7 days'),
        );

        $this->assertInstanceOf(Assignment::class, $assignment);
        $this->assertEquals('واجب البرمجة', $assignment->title());
        $this->assertEquals(AssignmentStatus::ASSIGNED, $assignment->status());
    }

    public function test_can_mark_assignment_as_submitted(): void
    {
        $assignment = Assignment::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('+7 days'),
        );

        $assignment->markAsSubmitted('https://example.com/submission');

        $this->assertEquals(AssignmentStatus::SUBMITTED, $assignment->status());
        $this->assertEquals('https://example.com/submission', $assignment->submissionUrl());
    }

    public function test_can_mark_assignment_as_late(): void
    {
        $assignment = Assignment::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('+7 days'),
        );

        $assignment->markAsLate();

        $this->assertEquals(AssignmentStatus::LATE, $assignment->status());
    }

    public function test_can_grade_assignment(): void
    {
        $assignment = Assignment::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('+7 days'),
        );

        $assignment->markAsSubmitted('https://example.com/submission');
        $assignment->assignGrade('A');

        $this->assertEquals(AssignmentStatus::GRADED, $assignment->status());
        $this->assertEquals('A', $assignment->grade());
    }

    public function test_assignment_is_overdue(): void
    {
        $assignment = Assignment::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('-1 day'),
        );

        $this->assertTrue($assignment->isOverdue());
    }

    public function test_can_convert_to_array(): void
    {
        $assignment = Assignment::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('+7 days'),
        );

        $array = $assignment->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('status', $array);
    }
}
