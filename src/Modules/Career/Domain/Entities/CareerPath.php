<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Entities;

use DateTimeImmutable;
use Modules\Career\Domain\Events\CareerPathCreated;
use Modules\Career\Domain\ValueObjects\CareerPathId;

final class CareerPath
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly CareerPathId $id,
        private string $title,
        private string $description,
        private string $targetRole,
        private array $requiredSkills,
        private array $stages,
        private ?string $averageSalary,
        private ?string $growthRate,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        CareerPathId $id,
        string $title,
        string $description,
        string $targetRole,
        array $requiredSkills = [],
        array $stages = [],
        ?string $averageSalary = null,
        ?string $growthRate = null,
    ): self {
        $now = new DateTimeImmutable;

        $path = new self(
            $id,
            $title,
            $description,
            $targetRole,
            $requiredSkills,
            $stages,
            $averageSalary,
            $growthRate,
            $now,
            $now,
        );

        $path->raise(new CareerPathCreated(
            $id->value(),
            $title,
            $targetRole,
            $now,
        ));

        return $path;
    }

    public static function reconstitute(
        CareerPathId $id,
        string $title,
        string $description,
        string $targetRole,
        array $requiredSkills,
        array $stages,
        ?string $averageSalary,
        ?string $growthRate,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $title,
            $description,
            $targetRole,
            $requiredSkills,
            $stages,
            $averageSalary,
            $growthRate,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): CareerPathId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function targetRole(): string
    {
        return $this->targetRole;
    }

    public function requiredSkills(): array
    {
        return $this->requiredSkills;
    }

    /** @return array<CareerPathStage> */
    public function stages(): array
    {
        return $this->stages;
    }

    public function averageSalary(): ?string
    {
        return $this->averageSalary;
    }

    public function growthRate(): ?string
    {
        return $this->growthRate;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function addStage(CareerPathStage $stage): void
    {
        $this->stages[] = $stage;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function getTotalDuration(): int
    {
        $total = 0;

        foreach ($this->stages as $stage) {
            $total += $stage->durationMonths();
        }

        return $total;
    }

    public function getAllRequiredSkills(): array
    {
        $skills = $this->requiredSkills;

        foreach ($this->stages as $stage) {
            foreach ($stage->requiredSkills() as $skill) {
                if (! in_array($skill, $skills, true)) {
                    $skills[] = $skill;
                }
            }
        }

        return array_values($skills);
    }

    public function updateDescription(string $description): void
    {
        $this->description = $description;
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
