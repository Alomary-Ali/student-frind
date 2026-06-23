<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Entities;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;

final class PortfolioItem
{
    private function __construct(
        private readonly PortfolioItemId $id,
        private readonly CareerProfileId $careerProfileId,
        private string $title,
        private string $description,
        private ?string $projectUrl,
        private ?string $githubUrl,
        private DateTimeImmutable $startDate,
        private ?DateTimeImmutable $endDate,
        private array $technologies,
    ) {}

    public static function create(
        PortfolioItemId $id,
        CareerProfileId $careerProfileId,
        string $title,
        string $description,
        ?string $projectUrl,
        ?string $githubUrl,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        array $technologies,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $title,
            $description,
            $projectUrl,
            $githubUrl,
            $startDate,
            $endDate,
            $technologies,
        );
    }

    public static function reconstitute(
        PortfolioItemId $id,
        CareerProfileId $careerProfileId,
        string $title,
        string $description,
        ?string $projectUrl,
        ?string $githubUrl,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        array $technologies,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $title,
            $description,
            $projectUrl,
            $githubUrl,
            $startDate,
            $endDate,
            $technologies,
        );
    }

    public function id(): PortfolioItemId
    {
        return $this->id;
    }

    public function careerProfileId(): CareerProfileId
    {
        return $this->careerProfileId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function projectUrl(): ?string
    {
        return $this->projectUrl;
    }

    public function githubUrl(): ?string
    {
        return $this->githubUrl;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function technologies(): array
    {
        return $this->technologies;
    }

    public function update(
        string $title,
        string $description,
        ?string $projectUrl,
        ?string $githubUrl,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        array $technologies,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->projectUrl = $projectUrl;
        $this->githubUrl = $githubUrl;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->technologies = $technologies;
    }
}
