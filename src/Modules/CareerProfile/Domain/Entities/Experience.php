<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Entities;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;

final class Experience
{
    private function __construct(
        private readonly ExperienceId $id,
        private readonly CareerProfileId $careerProfileId,
        private string $company,
        private string $position,
        private string $description,
        private DateTimeImmutable $startDate,
        private ?DateTimeImmutable $endDate,
        private bool $isCurrent,
    ) {}

    public static function create(
        ExperienceId $id,
        CareerProfileId $careerProfileId,
        string $company,
        string $position,
        string $description,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        bool $isCurrent,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $company,
            $position,
            $description,
            $startDate,
            $endDate,
            $isCurrent
        );
    }

    public static function reconstitute(
        ExperienceId $id,
        CareerProfileId $careerProfileId,
        string $company,
        string $position,
        string $description,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        bool $isCurrent,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $company,
            $position,
            $description,
            $startDate,
            $endDate,
            $isCurrent
        );
    }

    public function id(): ExperienceId
    {
        return $this->id;
    }

    public function careerProfileId(): CareerProfileId
    {
        return $this->careerProfileId;
    }

    public function company(): string
    {
        return $this->company;
    }

    public function position(): string
    {
        return $this->position;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }

    public function update(
        string $company,
        string $position,
        string $description,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        bool $isCurrent,
    ): void {
        $this->company = $company;
        $this->position = $position;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->isCurrent = $isCurrent;
    }
}
