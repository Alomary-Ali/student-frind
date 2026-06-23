<?php

declare(strict_types=1);

namespace Modules\Opportunities\Tests\Unit\Domain\ValueObjects;

use InvalidArgumentException;
use Modules\Opportunities\Domain\Exceptions\InvalidOpportunityIdException;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityScore;
use PHPUnit\Framework\TestCase;

final class OpportunityValueObjectsTest extends TestCase
{
    public function test_can_generate_opportunity_id(): void
    {
        $id = OpportunityId::generate();

        $this->assertNotNull($id->value());
        $this->assertIsString($id->value());
    }

    public function test_can_create_opportunity_id_from_string(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $id = OpportunityId::fromString($uuid);

        $this->assertSame($uuid, $id->value());
    }

    public function test_throws_exception_for_invalid_opportunity_id(): void
    {
        $this->expectException(InvalidOpportunityIdException::class);

        OpportunityId::fromString('invalid-uuid');
    }

    public function test_opportunity_id_equality(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $id1 = OpportunityId::fromString($uuid);
        $id2 = OpportunityId::fromString($uuid);
        $id3 = OpportunityId::generate();

        $this->assertTrue($id1->equals($id2));
        $this->assertFalse($id1->equals($id3));
    }

    public function test_opportunity_id_to_string(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $id = OpportunityId::fromString($uuid);

        $this->assertSame($uuid, (string) $id);
    }

    public function test_opportunity_id_of_alias(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $id = OpportunityId::of($uuid);

        $this->assertSame($uuid, $id->value());
    }

    public function test_opportunity_score_from_float(): void
    {
        $score = OpportunityScore::fromFloat(75.5);

        $this->assertSame(75.5, $score->value());
    }

    public function test_opportunity_score_throws_for_out_of_range(): void
    {
        $this->expectException(InvalidArgumentException::class);

        OpportunityScore::fromFloat(150);
    }

    public function test_opportunity_score_percentage(): void
    {
        $score = OpportunityScore::fromFloat(75.5);

        $this->assertSame('75.5%', $score->percentage());
    }

    public function test_opportunity_score_levels(): void
    {
        $high = OpportunityScore::fromFloat(85);
        $medium = OpportunityScore::fromFloat(55);
        $low = OpportunityScore::fromFloat(20);

        $this->assertTrue($high->isHigh());
        $this->assertFalse($high->isLow());

        $this->assertTrue($medium->isMedium());
        $this->assertFalse($medium->isHigh());

        $this->assertTrue($low->isLow());
        $this->assertFalse($low->isHigh());
    }
}
