<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Events;

use DateTimeImmutable;

final readonly class KnowledgeArticlePublished
{
    public function __construct(
        public string $articleId,
        public string $categoryId,
        public string $title,
        public DateTimeImmutable $publishedAt,
    ) {}
}
