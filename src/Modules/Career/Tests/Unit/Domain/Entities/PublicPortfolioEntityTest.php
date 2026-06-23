<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Domain\Entities;

use Modules\Career\Domain\Entities\PublicPortfolio;
use Modules\Career\Domain\Enums\PortfolioTheme;
use Modules\Career\Domain\Events\PortfolioPublished;
use Modules\Career\Domain\ValueObjects\PortfolioSlug;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;
use PHPUnit\Framework\TestCase;

final class PublicPortfolioEntityTest extends TestCase
{
    public function test_create_returns_portfolio_with_defaults(): void
    {
        $id = PublicPortfolioId::generate();
        $slug = PortfolioSlug::fromString('my-portfolio');

        $portfolio = PublicPortfolio::create($id, 'student-1', $slug, 'معرضي');

        $this->assertSame($id, $portfolio->id());
        $this->assertSame('student-1', $portfolio->studentId());
        $this->assertSame('my-portfolio', $portfolio->slug()->value());
        $this->assertSame('معرضي', $portfolio->title());
        $this->assertSame(PortfolioTheme::MODERN, $portfolio->theme());
        $this->assertFalse($portfolio->isActive());
        $this->assertSame(0, $portfolio->viewsCount());
    }

    public function test_publish_activates_and_dispatches_event(): void
    {
        $portfolio = PublicPortfolio::create(
            PublicPortfolioId::generate(),
            'student-1',
            PortfolioSlug::fromString('my-portfolio'),
            'معرضي',
        );
        $portfolio->releaseEvents();

        $portfolio->publish();

        $this->assertTrue($portfolio->isActive());

        $events = $portfolio->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(PortfolioPublished::class, $events[0]);
    }

    public function test_unpublish_deactivates(): void
    {
        $portfolio = PublicPortfolio::create(
            PublicPortfolioId::generate(),
            'student-1',
            PortfolioSlug::fromString('my-portfolio'),
            'معرضي',
        );
        $portfolio->publish();
        $portfolio->releaseEvents();

        $portfolio->unpublish();

        $this->assertFalse($portfolio->isActive());
    }

    public function test_increment_views(): void
    {
        $portfolio = PublicPortfolio::create(
            PublicPortfolioId::generate(),
            'student-1',
            PortfolioSlug::fromString('my-portfolio'),
            'معرضي',
        );

        $portfolio->incrementViews();
        $portfolio->incrementViews();
        $portfolio->incrementViews();

        $this->assertSame(3, $portfolio->viewsCount());
    }

    public function test_update_theme(): void
    {
        $portfolio = PublicPortfolio::create(
            PublicPortfolioId::generate(),
            'student-1',
            PortfolioSlug::fromString('my-portfolio'),
            'معرضي',
        );

        $portfolio->updateTheme(PortfolioTheme::CREATIVE);

        $this->assertSame(PortfolioTheme::CREATIVE, $portfolio->theme());
    }

    public function test_update_profile(): void
    {
        $portfolio = PublicPortfolio::create(
            PublicPortfolioId::generate(),
            'student-1',
            PortfolioSlug::fromString('my-portfolio'),
            'معرضي',
        );
        $newSlug = PortfolioSlug::fromString('new-slug');

        $portfolio->updateProfile('عنوان جديد', 'نبذة جديدة', $newSlug);

        $this->assertSame('عنوان جديد', $portfolio->title());
        $this->assertSame('نبذة جديدة', $portfolio->bio());
        $this->assertSame('new-slug', $portfolio->slug()->value());
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = PublicPortfolioId::generate();
        $slug = PortfolioSlug::fromString('my-portfolio');
        $now = new \DateTimeImmutable;

        $portfolio = PublicPortfolio::reconstitute(
            $id,
            'student-1',
            $slug,
            'معرضي',
            'نبذة',
            PortfolioTheme::MINIMAL,
            true,
            42,
            $now,
            $now,
        );

        $this->assertSame($id->value(), $portfolio->id()->value());
        $this->assertTrue($portfolio->isActive());
        $this->assertSame(42, $portfolio->viewsCount());
        $this->assertSame(PortfolioTheme::MINIMAL, $portfolio->theme());
    }
}
