<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Enums\KnowledgeStatus;
use Modules\StudentServices\Domain\Events\KnowledgeArticlePublished;
use Modules\StudentServices\Domain\ValueObjects\KnowledgeArticleId;

final class KnowledgeArticle
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly KnowledgeArticleId $id,
        private readonly string $categoryId,
        private string $title,
        private string $slug,
        private string $content,
        private array $tags,
        private KnowledgeStatus $status,
        private int $viewCount,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        KnowledgeArticleId $id,
        string $categoryId,
        string $title,
        string $slug,
        string $content,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;

        return new self(
            $id,
            $categoryId,
            $title,
            $slug,
            $content,
            $tags,
            KnowledgeStatus::DRAFT,
            0,
            $now,
            $now,
        );
    }

    public static function reconstitute(
        KnowledgeArticleId $id,
        string $categoryId,
        string $title,
        string $slug,
        string $content,
        array $tags,
        KnowledgeStatus $status,
        int $viewCount,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $categoryId,
            $title,
            $slug,
            $content,
            $tags,
            $status,
            $viewCount,
            $createdAt,
            $updatedAt,
        );
    }

    public function publish(): void
    {
        $this->status = KnowledgeStatus::PUBLISHED;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new KnowledgeArticlePublished(
            $this->id->value(),
            $this->categoryId,
            $this->title,
            $this->updatedAt,
        ));
    }

    public function archive(): void
    {
        $this->status = KnowledgeStatus::ARCHIVED;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function incrementViews(): void
    {
        $this->viewCount++;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function id(): KnowledgeArticleId
    {
        return $this->id;
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function tags(): array
    {
        return $this->tags;
    }

    public function status(): KnowledgeStatus
    {
        return $this->status;
    }

    public function viewCount(): int
    {
        return $this->viewCount;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
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
