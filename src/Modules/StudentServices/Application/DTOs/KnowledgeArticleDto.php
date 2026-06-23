<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class KnowledgeArticleDto
{
    public function __construct(
        public string $id,
        public string $categoryId,
        public string $title,
        public string $slug,
        public string $content,
        public array $tags,
        public string $status,
        public int $viewCount,
        public string $createdAt,
    ) {}
}
