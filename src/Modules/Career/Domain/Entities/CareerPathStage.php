<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Entities;

use Modules\Career\Domain\ValueObjects\CareerPathStageId;

final class CareerPathStage
{
    private function __construct(
        private readonly CareerPathStageId $id,
        private string $title,
        private int $order,
        private array $requiredSkills,
        private int $durationMonths,
        private ?string $salaryRange,
        private ?string $description,
    ) {}

    public static function create(
        CareerPathStageId $id,
        string $title,
        int $order,
        array $requiredSkills,
        int $durationMonths,
        ?string $salaryRange = null,
        ?string $description = null,
    ): self {
        return new self(
            $id,
            $title,
            $order,
            $requiredSkills,
            $durationMonths,
            $salaryRange,
            $description,
        );
    }

    public static function reconstitute(
        CareerPathStageId $id,
        string $title,
        int $order,
        array $requiredSkills,
        int $durationMonths,
        ?string $salaryRange,
        ?string $description,
    ): self {
        return new self(
            $id,
            $title,
            $order,
            $requiredSkills,
            $durationMonths,
            $salaryRange,
            $description,
        );
    }

    public function update(
        string $title,
        int $order,
        array $requiredSkills,
        int $durationMonths,
        ?string $salaryRange = null,
        ?string $description = null,
    ): void {
        $this->title = $title;
        $this->order = $order;
        $this->requiredSkills = $requiredSkills;
        $this->durationMonths = $durationMonths;
        $this->salaryRange = $salaryRange;
        $this->description = $description;
    }

    public function id(): CareerPathStageId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function order(): int
    {
        return $this->order;
    }

    public function requiredSkills(): array
    {
        return $this->requiredSkills;
    }

    public function durationMonths(): int
    {
        return $this->durationMonths;
    }

    public function salaryRange(): ?string
    {
        return $this->salaryRange;
    }

    public function description(): ?string
    {
        return $this->description;
    }
}
