<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\KnowledgeRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\KnowledgeArticleId;

final readonly class UpdateKnowledgeArticle
{
    public function __construct(
        private KnowledgeRepositoryInterface $articles,
    ) {}

    public function execute(string $articleId, array $data): ?array
    {
        $id = KnowledgeArticleId::fromString($articleId);
        $article = $this->articles->findArticleById($id);

        if ($article === null) {
            return null;
        }

        $this->articles->saveArticle($article);

        return [
            'id' => $article->id()->value(),
            'category_id' => $article->categoryId(),
            'title' => $article->title(),
            'slug' => $article->slug(),
            'status' => $article->status()->value,
            'updated_at' => $article->updatedAt()->format('c'),
        ];
    }
}
