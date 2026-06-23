<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Entities\PortfolioItem;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use PHPUnit\Framework\TestCase;

final class PortfolioItemEntityTest extends TestCase
{
    private PortfolioItemId $itemId;
    private CareerProfileId $profileId;

    protected function setUp(): void
    {
        $this->itemId = PortfolioItemId::generate();
        $this->profileId = CareerProfileId::generate();
    }

    public function test_can_create_portfolio_item(): void
    {
        $item = PortfolioItem::create(
            $this->itemId,
            $this->profileId,
            'مشروع اختبار',
            'وصف المشروع',
            'https://example.com',
            'https://github.com/test',
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-06-01'),
            ['Laravel', 'Vue.js'],
        );

        $this->assertSame($this->itemId, $item->id());
        $this->assertSame($this->profileId, $item->careerProfileId());
        $this->assertSame('مشروع اختبار', $item->title());
        $this->assertSame('وصف المشروع', $item->description());
        $this->assertSame('https://example.com', $item->projectUrl());
        $this->assertSame('https://github.com/test', $item->githubUrl());
        $this->assertSame(['Laravel', 'Vue.js'], $item->technologies());
    }

    public function test_can_create_with_null_optionals(): void
    {
        $item = PortfolioItem::create(
            $this->itemId,
            $this->profileId,
            'مشروع',
            'وصف',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            [],
        );

        $this->assertNull($item->projectUrl());
        $this->assertNull($item->githubUrl());
        $this->assertNull($item->endDate());
        $this->assertEmpty($item->technologies());
    }

    public function test_can_update_portfolio_item(): void
    {
        $item = PortfolioItem::create(
            $this->itemId,
            $this->profileId,
            'عنوان قديم',
            'وصف قديم',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            [],
        );

        $item->update(
            'عنوان جديد',
            'وصف جديد',
            'https://new-url.com',
            'https://github.com/new',
            new DateTimeImmutable('2026-03-01'),
            new DateTimeImmutable('2026-09-01'),
            ['React', 'TypeScript'],
        );

        $this->assertSame('عنوان جديد', $item->title());
        $this->assertSame('وصف جديد', $item->description());
        $this->assertSame('https://new-url.com', $item->projectUrl());
        $this->assertSame('https://github.com/new', $item->githubUrl());
        $this->assertSame(['React', 'TypeScript'], $item->technologies());
    }

    public function test_can_reconstitute_portfolio_item(): void
    {
        $startDate = new DateTimeImmutable('2026-01-01');
        $item = PortfolioItem::reconstitute(
            $this->itemId,
            $this->profileId,
            'مشروع',
            'وصف',
            'https://project.com',
            null,
            $startDate,
            null,
            ['PHP'],
        );

        $this->assertSame('مشروع', $item->title());
        $this->assertSame($startDate, $item->startDate());
        $this->assertSame(['PHP'], $item->technologies());
    }
}
