<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\RequestPriority;
use Modules\StudentServices\Domain\Enums\ServiceStatus;
use Modules\StudentServices\Domain\Events\ServiceRequestApproved;
use Modules\StudentServices\Domain\Events\ServiceRequestCancelled;
use Modules\StudentServices\Domain\Events\ServiceRequestCompleted;
use Modules\StudentServices\Domain\Events\ServiceRequestRejected;
use Modules\StudentServices\Domain\Events\ServiceRequestReviewed;
use Modules\StudentServices\Domain\Events\ServiceRequestSubmitted;
use Modules\StudentServices\Domain\Exceptions\InvalidServiceStatusTransitionException;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;

final class ServiceRequest
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly ServiceRequestId $id,
        private readonly string $studentId,
        private readonly string $categoryId,
        private readonly string $refNumber,
        private ServiceStatus $status,
        private RequestPriority $priority,
        private ?string $notes,
        private ?string $adminNotes,
        private ?string $workflowId,
        private ?string $currentStepId,
        private array $attachments,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        ServiceRequestId $id,
        string $studentId,
        string $categoryId,
        string $refNumber,
        RequestPriority $priority = RequestPriority::MEDIUM,
        ?string $notes = null,
    ): self {
        $now = new DateTimeImmutable;

        $request = new self(
            $id,
            $studentId,
            $categoryId,
            $refNumber,
            ServiceStatus::NEW,
            $priority,
            $notes,
            null,
            null,
            null,
            [],
            $now,
            $now,
        );

        $request->raise(new ServiceRequestSubmitted(
            $id->value(),
            $studentId,
            $categoryId,
            ServiceStatus::NEW->value,
            $now,
        ));

        return $request;
    }

    public static function reconstitute(
        ServiceRequestId $id,
        string $studentId,
        string $categoryId,
        string $refNumber,
        ServiceStatus $status,
        RequestPriority $priority,
        ?string $notes,
        ?string $adminNotes,
        ?string $workflowId,
        ?string $currentStepId,
        array $attachments,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $categoryId,
            $refNumber,
            $status,
            $priority,
            $notes,
            $adminNotes,
            $workflowId,
            $currentStepId,
            $attachments,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): ServiceRequestId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function refNumber(): string
    {
        return $this->refNumber;
    }

    public function status(): ServiceStatus
    {
        return $this->status;
    }

    public function priority(): RequestPriority
    {
        return $this->priority;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function adminNotes(): ?string
    {
        return $this->adminNotes;
    }

    public function workflowId(): ?string
    {
        return $this->workflowId;
    }

    public function currentStepId(): ?string
    {
        return $this->currentStepId;
    }

    public function attachments(): array
    {
        return $this->attachments;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function submitForReview(string $reviewerId, ?string $notes = null): void
    {
        $this->assertTransition(ServiceStatus::UNDER_REVIEW);
        $this->status = ServiceStatus::UNDER_REVIEW;
        $this->adminNotes = $notes;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new ServiceRequestReviewed(
            $this->id->value(),
            $this->studentId,
            $reviewerId,
            $this->status->value,
            $notes ?? '',
            $this->updatedAt,
        ));
    }

    public function approve(string $reviewerId): void
    {
        $this->assertTransition(ServiceStatus::APPROVED);
        $this->status = ServiceStatus::APPROVED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new ServiceRequestApproved(
            $this->id->value(),
            $this->studentId,
            $reviewerId,
            $this->updatedAt,
        ));
    }

    public function reject(string $reviewerId, string $reason): void
    {
        $this->assertTransition(ServiceStatus::REJECTED);
        $this->status = ServiceStatus::REJECTED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new ServiceRequestRejected(
            $this->id->value(),
            $this->studentId,
            $reviewerId,
            $reason,
            $this->updatedAt,
        ));
    }

    public function complete(): void
    {
        $this->assertTransition(ServiceStatus::COMPLETED);
        $this->status = ServiceStatus::COMPLETED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new ServiceRequestCompleted(
            $this->id->value(),
            $this->studentId,
            $this->updatedAt,
        ));
    }

    public function cancel(string $reason): void
    {
        $this->assertTransition(ServiceStatus::CANCELLED);
        $this->status = ServiceStatus::CANCELLED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new ServiceRequestCancelled(
            $this->id->value(),
            $this->studentId,
            $reason,
            $this->updatedAt,
        ));
    }

    public function addNote(string $note): void
    {
        $this->notes = $note;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function addAttachment(string $path): void
    {
        $this->attachments[] = $path;
        $this->updatedAt = new DateTimeImmutable;
    }

    private function assertTransition(ServiceStatus $target): void
    {
        $allowed = match ($this->status) {
            ServiceStatus::NEW => [ServiceStatus::UNDER_REVIEW, ServiceStatus::CANCELLED],
            ServiceStatus::UNDER_REVIEW => [ServiceStatus::APPROVED, ServiceStatus::REJECTED, ServiceStatus::CANCELLED],
            ServiceStatus::APPROVED => [ServiceStatus::COMPLETED, ServiceStatus::CANCELLED],
            ServiceStatus::REJECTED => [],
            ServiceStatus::COMPLETED => [],
            ServiceStatus::CANCELLED => [],
        };

        if (! in_array($target, $allowed, true)) {
            throw InvalidServiceStatusTransitionException::transitionNotAllowed(
                $this->status->value,
                $target->value,
            );
        }
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
