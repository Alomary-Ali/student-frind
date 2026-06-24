<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Entities\KnowledgeArticle;
use Modules\StudentServices\Domain\Enums\KnowledgeStatus;
use Modules\StudentServices\Domain\Events\KnowledgeArticlePublished;
use Modules\StudentServices\Domain\ValueObjects\KnowledgeArticleId;
use PHPUnit\Framework\TestCase;

final class KnowledgeArticleEntityTest extends TestCase
{
    public function test_create_returns_article_with_draft_status(): void
    {
        $id = KnowledgeArticleId::generate();
        $article = KnowledgeArticle::create($id, 'category-1', 'كيفية التسجيل', 'how-to-register', 'محتوى المقال');

        $this->assertSame($id, $article->id());
        $this->assertSame('category-1', $article->categoryId());
        $this->assertSame('كيفية التسجيل', $article->title());
        $this->assertSame('how-to-register', $article->slug());
        $this->assertSame('محتوى المقال', $article->content());
        $this->assertSame(KnowledgeStatus::DRAFT, $article->status());
        $this->assertSame(0, $article->viewCount());
        $this->assertEmpty($article->tags());
    }

    public function test_create_with_tags(): void
    {
        $article = KnowledgeArticle::create(
            KnowledgeArticleId::generate(),
            'category-1',
            'عنوان',
            'slug',
            'content',
            ['tag1', 'tag2'],
        );

        $this->assertSame(['tag1', 'tag2'], $article->tags());
    }

    public function test_publish_changes_status_and_dispatches_event(): void
    {
        $id = KnowledgeArticleId::generate();
        $article = KnowledgeArticle::create($id, 'category-1', 'عنوان', 'slug', 'content');

        $article->publish();

        $this->assertSame(KnowledgeStatus::PUBLISHED, $article->status());

        $events = $article->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(KnowledgeArticlePublished::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->articleId);
        $this->assertSame('category-1', $events[0]->categoryId);
    }

    public function test_archive_changes_status(): void
    {
        $article = KnowledgeArticle::create(KnowledgeArticleId::generate(), 'category-1', 'عنوان', 'slug', 'content');
        $article->publish();
        $article->releaseEvents();

        $article->archive();

        $this->assertSame(KnowledgeStatus::ARCHIVED, $article->status());
    }

    public function test_increment_views_increases_count(): void
    {
        $article = KnowledgeArticle::create(KnowledgeArticleId::generate(), 'category-1', 'عنوان', 'slug', 'content');

        $article->incrementViews();
        $article->incrementViews();

        $this->assertSame(2, $article->viewCount());
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = KnowledgeArticleId::generate();
        $now = new DateTimeImmutable;

        $article = KnowledgeArticle::reconstitute(
            id: $id,
            categoryId: 'category-1',
            title: 'عنوان',
            slug: 'slug',
            content: 'content',
            tags: ['tag1', 'tag2'],
            status: KnowledgeStatus::PUBLISHED,
            viewCount: 100,
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id->value(), $article->id()->value());
        $this->assertSame(KnowledgeStatus::PUBLISHED, $article->status());
        $this->assertSame(100, $article->viewCount());
        $this->assertSame(['tag1', 'tag2'], $article->tags());
    }

    public function test_release_events_clears_events(): void
    {
        $article = KnowledgeArticle::create(KnowledgeArticleId::generate(), 'category-1', 'عنوان', 'slug', 'content');
        $article->publish();

        $this->assertCount(1, $article->releaseEvents());
        $this->assertCount(0, $article->releaseEvents());
    }
}
