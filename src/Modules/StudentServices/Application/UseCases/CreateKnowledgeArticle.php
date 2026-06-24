<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Illuminate\Support\Str;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\KnowledgeRepositoryInterface;
use Modules\StudentServices\Domain\Entities\KnowledgeArticle;
use Modules\StudentServices\Domain\ValueObjects\KnowledgeArticleId;

final readonly class CreateKnowledgeArticle
{
    public function __construct(
        private KnowledgeRepositoryInterface $articles,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $categoryId, string $title, string $content, array $tags = [], string $status = 'draft'): array
    {
        $article = KnowledgeArticle::create(
            id: KnowledgeArticleId::generate(),
            categoryId: $categoryId,
            title: $title,
            slug: Str::slug($title),
            content: $content,
            tags: $tags,
        );

        if ($status === 'published') {
            $article->publish();
        }

        $this->articles->saveArticle($article);
        $this->events->dispatch($article->releaseEvents());

        return [
            'id' => $article->id()->value(),
            'category_id' => $article->categoryId(),
            'title' => $article->title(),
            'slug' => $article->slug(),
            'status' => $article->status()->value,
            'tags' => $article->tags(),
            'created_at' => $article->createdAt()->format('c'),
        ];
    }
}
