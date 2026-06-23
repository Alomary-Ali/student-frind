<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Entities;

use DateTimeImmutable;
use Modules\Career\Domain\Enums\PortfolioTheme;
use Modules\Career\Domain\Events\PortfolioPublished;
use Modules\Career\Domain\ValueObjects\PortfolioSlug;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;

final class PublicPortfolio
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly PublicPortfolioId $id,
        private readonly string $studentId,
        private PortfolioSlug $slug,
        private string $title,
        private ?string $bio,
        private PortfolioTheme $theme,
        private bool $isActive,
        private int $viewsCount,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        PublicPortfolioId $id,
        string $studentId,
        PortfolioSlug $slug,
        string $title,
        ?string $bio = null,
    ): self {
        $now = new DateTimeImmutable;

        return new self(
            $id,
            $studentId,
            $slug,
            $title,
            $bio,
            PortfolioTheme::MODERN,
            false,
            0,
            $now,
            $now,
        );
    }

    public static function reconstitute(
        PublicPortfolioId $id,
        string $studentId,
        PortfolioSlug $slug,
        string $title,
        ?string $bio,
        PortfolioTheme $theme,
        bool $isActive,
        int $viewsCount,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $slug,
            $title,
            $bio,
            $theme,
            $isActive,
            $viewsCount,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): PublicPortfolioId
    {
        return $this->id;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function slug(): PortfolioSlug
    {
        return $this->slug;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function bio(): ?string
    {
        return $this->bio;
    }

    public function theme(): PortfolioTheme
    {
        return $this->theme;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function viewsCount(): int
    {
        return $this->viewsCount;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function publish(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new PortfolioPublished(
            $this->id->value(),
            $this->studentId,
            $this->slug->value(),
            $this->updatedAt,
        ));
    }

    public function unpublish(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function incrementViews(): void
    {
        $this->viewsCount++;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function updateTheme(PortfolioTheme $theme): void
    {
        $this->theme = $theme;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function updateProfile(string $title, ?string $bio, PortfolioSlug $slug): void
    {
        $this->title = $title;
        $this->bio = $bio;
        $this->slug = $slug;
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
