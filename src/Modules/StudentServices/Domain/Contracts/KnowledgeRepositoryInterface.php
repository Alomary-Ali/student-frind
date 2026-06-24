<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts;

use Modules\StudentServices\Domain\Entities\KnowledgeArticle;
use Modules\StudentServices\Domain\ValueObjects\KnowledgeArticleId;

interface KnowledgeRepositoryInterface
{
    public function findArticleById(KnowledgeArticleId $id): ?KnowledgeArticle;

    public function findArticlesByCategory(string $categoryId): array;

    public function searchArticles(string $query): array;

    public function findAllPublished(): array;

    public function saveArticle(KnowledgeArticle $article): void;

    public function deleteArticle(KnowledgeArticleId $id): void;
}
