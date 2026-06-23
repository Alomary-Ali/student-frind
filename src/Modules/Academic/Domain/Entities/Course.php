<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Events\CourseCreated;
use Modules\Academic\Domain\Exceptions\CourseNotActiveException;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;

final class Course
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly CourseId $id,
        private readonly string $code,
        private readonly string $title,
        private readonly string $description,
        private readonly Credits $creditHours,
        private bool $isActive,
        private readonly ?string $institutionId,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        CourseId $id,
        string $code,
        string $title,
        string $description,
        Credits $creditHours,
        ?string $institutionId = null,
    ): self {
        $course = new self(
            id: $id,
            code: $code,
            title: $title,
            description: $description,
            creditHours: $creditHours,
            isActive: true,
            institutionId: $institutionId,
            createdAt: new DateTimeImmutable,
        );

        $course->raise(new CourseCreated(
            courseId: $id->value(),
            code: $code,
            title: $title,
            creditHours: $creditHours->value(),
            occurredAt: new DateTimeImmutable,
        ));

        return $course;
    }

    public static function reconstitute(
        CourseId $id,
        string $code,
        string $title,
        string $description,
        Credits $creditHours,
        bool $isActive,
        ?string $institutionId,
        DateTimeImmutable $createdAt,
    ): self {
        return new self($id, $code, $title, $description, $creditHours, $isActive, $institutionId, $createdAt);
    }

    public function ensureActive(): void
    {
        if (! $this->isActive) {
            throw CourseNotActiveException::forCourse($this->id->value());
        }
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function id(): CourseId
    {
        return $this->id;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function creditHours(): Credits
    {
        return $this->creditHours;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function institutionId(): ?string
    {
        return $this->institutionId;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
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
