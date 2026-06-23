<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Entities;

use DateTimeImmutable;
use Modules\Career\Domain\Enums\InterviewStatus;
use Modules\Career\Domain\Enums\InterviewType;
use Modules\Career\Domain\Events\InterviewCompleted;
use Modules\Career\Domain\Events\InterviewScheduled;
use Modules\Career\Domain\ValueObjects\InterviewId;

final class Interview
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly InterviewId $id,
        private readonly string $studentId,
        private InterviewType $type,
        private InterviewStatus $status,
        private readonly DateTimeImmutable $scheduledAt,
        private array $questions,
        private ?int $score,
        private ?string $feedback,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        InterviewId $id,
        string $studentId,
        InterviewType $type,
        DateTimeImmutable $scheduledAt,
    ): self {
        $now = new DateTimeImmutable;

        $interview = new self(
            $id,
            $studentId,
            $type,
            InterviewStatus::SCHEDULED,
            $scheduledAt,
            [],
            null,
            null,
            $now,
            $now,
        );

        $interview->raise(new InterviewScheduled(
            $id->value(),
            $studentId,
            $type->value,
            $scheduledAt,
            $now,
        ));

        return $interview;
    }

    public static function reconstitute(
        InterviewId $id,
        string $studentId,
        InterviewType $type,
        InterviewStatus $status,
        DateTimeImmutable $scheduledAt,
        array $questions,
        ?int $score,
        ?string $feedback,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $type,
            $status,
            $scheduledAt,
            $questions,
            $score,
            $feedback,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): InterviewId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function type(): InterviewType
    {
        return $this->type;
    }

    public function status(): InterviewStatus
    {
        return $this->status;
    }

    public function scheduledAt(): DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function questions(): array
    {
        return $this->questions;
    }

    public function score(): ?int
    {
        return $this->score;
    }

    public function feedback(): ?string
    {
        return $this->feedback;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function addQuestion(array $questionData): void
    {
        $this->questions[] = $questionData;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function submitAttempt(int $score, ?string $feedback = null): void
    {
        $this->score = $score;
        $this->feedback = $feedback;
        $this->status = InterviewStatus::COMPLETED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new InterviewCompleted(
            $this->id->value(),
            $this->studentId,
            $score,
            $feedback,
            $this->updatedAt,
        ));
    }

    public function cancel(): void
    {
        $this->status = InterviewStatus::CANCELLED;
        $this->updatedAt = new DateTimeImmutable;
    }

    private function raise(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
