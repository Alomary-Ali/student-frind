<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Career\Domain\Entities\Interview;
use Modules\Career\Domain\Enums\InterviewStatus;
use Modules\Career\Domain\Enums\InterviewType;
use Modules\Career\Domain\Events\InterviewCompleted;
use Modules\Career\Domain\Events\InterviewScheduled;
use Modules\Career\Domain\ValueObjects\InterviewId;
use PHPUnit\Framework\TestCase;

final class InterviewEntityTest extends TestCase
{
    public function test_create_returns_interview_with_scheduled_status(): void
    {
        $id = InterviewId::generate();
        $scheduledAt = new DateTimeImmutable('2026-07-15 10:00:00');

        $interview = Interview::create($id, 'student-1', InterviewType::MOCK, $scheduledAt);

        $this->assertSame($id, $interview->id());
        $this->assertSame('student-1', $interview->studentId());
        $this->assertSame(InterviewType::MOCK, $interview->type());
        $this->assertSame(InterviewStatus::SCHEDULED, $interview->status());
        $this->assertSame($scheduledAt, $interview->scheduledAt());
        $this->assertNull($interview->score());
        $this->assertNull($interview->feedback());
        $this->assertEmpty($interview->questions());
    }

    public function test_create_dispatches_interview_scheduled_event(): void
    {
        $id = InterviewId::generate();
        $interview = Interview::create($id, 'student-1', InterviewType::TECHNICAL, new DateTimeImmutable('2026-07-15 10:00:00'));

        $events = $interview->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(InterviewScheduled::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->interviewId);
        $this->assertSame('student-1', $events[0]->studentId);
    }

    public function test_submit_attempt_marks_completed_and_dispatches_event(): void
    {
        $id = InterviewId::generate();
        $interview = Interview::create($id, 'student-1', InterviewType::BEHAVIORAL, new DateTimeImmutable('2026-07-15 10:00:00'));
        $interview->releaseEvents();

        $interview->submitAttempt(85, 'أداء ممتاز');

        $this->assertSame(InterviewStatus::COMPLETED, $interview->status());
        $this->assertSame(85, $interview->score());
        $this->assertSame('أداء ممتاز', $interview->feedback());

        $events = $interview->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(InterviewCompleted::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->interviewId);
        $this->assertSame(85, $events[0]->score);
    }

    public function test_cancel_changes_status(): void
    {
        $interview = Interview::create(InterviewId::generate(), 'student-1', InterviewType::GENERAL, new DateTimeImmutable('2026-07-15 10:00:00'));

        $interview->cancel();

        $this->assertSame(InterviewStatus::CANCELLED, $interview->status());
    }

    public function test_add_question_appends_to_questions(): void
    {
        $interview = Interview::create(InterviewId::generate(), 'student-1', InterviewType::TECHNICAL, new DateTimeImmutable('2026-07-15 10:00:00'));

        $interview->addQuestion(['id' => 'q-1', 'question' => 'ما هو REST API?', 'category' => 'technical', 'order' => 1]);
        $interview->addQuestion(['id' => 'q-2', 'question' => 'ما هي الفروق بين SQL و NoSQL?', 'category' => 'technical', 'order' => 2]);

        $this->assertCount(2, $interview->questions());
        $this->assertSame('ما هو REST API?', $interview->questions()[0]['question']);
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = InterviewId::generate();
        $now = new DateTimeImmutable;
        $scheduledAt = new DateTimeImmutable('2026-07-15 10:00:00');

        $interview = Interview::reconstitute(
            id: $id,
            studentId: 'student-1',
            type: InterviewType::TECHNICAL,
            status: InterviewStatus::COMPLETED,
            scheduledAt: $scheduledAt,
            questions: [['id' => 'q-1', 'question' => 'Test?', 'category' => null, 'order' => 1]],
            score: 90,
            feedback: 'جيد',
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id->value(), $interview->id()->value());
        $this->assertSame(InterviewStatus::COMPLETED, $interview->status());
        $this->assertSame(90, $interview->score());
    }

    public function test_release_events_clears_events(): void
    {
        $interview = Interview::create(InterviewId::generate(), 'student-1', InterviewType::MOCK, new DateTimeImmutable('2026-07-15 10:00:00'));

        $this->assertCount(1, $interview->releaseEvents());
        $this->assertCount(0, $interview->releaseEvents());
    }
}
