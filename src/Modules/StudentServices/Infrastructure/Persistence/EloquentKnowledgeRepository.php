<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Contracts\KnowledgeRepositoryInterface;
use Modules\StudentServices\Domain\Entities\KnowledgeArticle;
use Modules\StudentServices\Domain\Enums\KnowledgeStatus;
use Modules\StudentServices\Domain\ValueObjects\KnowledgeArticleId;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentKnowledgeArticle;

final class EloquentKnowledgeRepository implements KnowledgeRepositoryInterface
{
    public function findArticleById(KnowledgeArticleId $id): ?KnowledgeArticle
    {
        $model = EloquentKnowledgeArticle::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findArticlesByCategory(string $categoryId): array
    {
        return EloquentKnowledgeArticle::where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function searchArticles(string $query): array
    {
        return EloquentKnowledgeArticle::where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->orderBy('view_count', 'desc')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findAllPublished(): array
    {
        return EloquentKnowledgeArticle::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function saveArticle(KnowledgeArticle $article): void
    {
        $model = EloquentKnowledgeArticle::find($article->id()->value());

        if ($model === null) {
            $model = new EloquentKnowledgeArticle;
            $model->id = $article->id()->value();
        }

        $model->category_id = $article->categoryId();
        $model->title = $article->title();
        $model->slug = $article->slug();
        $model->content = $article->content();
        $model->tags = $article->tags();
        $model->status = $article->status()->value;
        $model->view_count = $article->viewCount();
        $model->save();
    }

    public function deleteArticle(KnowledgeArticleId $id): void
    {
        EloquentKnowledgeArticle::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentKnowledgeArticle $model): KnowledgeArticle
    {
        return KnowledgeArticle::reconstitute(
            id: KnowledgeArticleId::of($model->id),
            categoryId: $model->category_id,
            title: $model->title,
            slug: $model->slug,
            content: $model->content,
            tags: $model->tags ?? [],
            status: KnowledgeStatus::from($model->status),
            viewCount: (int) $model->view_count,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
