<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Domain\ValueObjects;

use Modules\Career\Domain\Exceptions\InvalidInterviewIdException;
use Modules\Career\Domain\Exceptions\InvalidPortfolioSlugException;
use Modules\Career\Domain\Exceptions\InvalidPublicPortfolioIdException;
use Modules\Career\Domain\ValueObjects\InterviewId;
use Modules\Career\Domain\ValueObjects\PortfolioSlug;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;
use PHPUnit\Framework\TestCase;

final class CareerValueObjectsTest extends TestCase
{
    public function test_interview_id_generate(): void
    {
        $id = InterviewId::generate();
        $this->assertIsString($id->value());
    }

    public function test_interview_id_from_string(): void
    {
        $uuid = '00000000-0000-0000-0000-000000000001';
        $id = InterviewId::fromString($uuid);
        $this->assertSame($uuid, $id->value());
    }

    public function test_interview_id_invalid_throws(): void
    {
        $this->expectException(InvalidInterviewIdException::class);
        InterviewId::fromString('not-a-uuid');
    }

    public function test_interview_id_equals(): void
    {
        $uuid = '00000000-0000-0000-0000-000000000001';
        $a = InterviewId::fromString($uuid);
        $b = InterviewId::fromString($uuid);
        $c = InterviewId::generate();

        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_interview_id_to_string(): void
    {
        $uuid = '00000000-0000-0000-0000-000000000001';
        $id = InterviewId::fromString($uuid);
        $this->assertSame($uuid, (string) $id);
    }

    public function test_interview_id_of(): void
    {
        $uuid = '00000000-0000-0000-0000-000000000001';
        $id = InterviewId::of($uuid);
        $this->assertSame($uuid, $id->value());
    }

    public function test_public_portfolio_id_generate(): void
    {
        $id = PublicPortfolioId::generate();
        $this->assertIsString($id->value());
    }

    public function test_public_portfolio_id_invalid_throws(): void
    {
        $this->expectException(InvalidPublicPortfolioIdException::class);
        PublicPortfolioId::fromString('bad');
    }

    public function test_portfolio_slug_valid(): void
    {
        $slug = PortfolioSlug::fromString('my-portfolio');
        $this->assertSame('my-portfolio', $slug->value());
    }

    public function test_portfolio_slug_invalid_throws(): void
    {
        $this->expectException(InvalidPortfolioSlugException::class);
        PortfolioSlug::fromString(''); // Empty string is invalid
    }

    public function test_portfolio_slug_equals(): void
    {
        $a = PortfolioSlug::fromString('my-portfolio');
        $b = PortfolioSlug::fromString('my-portfolio');
        $c = PortfolioSlug::fromString('other-slug');

        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_portfolio_slug_to_string(): void
    {
        $slug = PortfolioSlug::fromString('my-portfolio');
        $this->assertSame('my-portfolio', (string) $slug);
    }
}
