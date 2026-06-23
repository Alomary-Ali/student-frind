<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\ValueObjects;

use Modules\CareerProfile\Domain\Exceptions\InvalidCareerGoalIdException;
use Modules\CareerProfile\Domain\Exceptions\InvalidCareerProfileIdException;
use Modules\CareerProfile\Domain\Exceptions\InvalidExperienceIdException;
use Modules\CareerProfile\Domain\Exceptions\InvalidPortfolioItemIdException;
use Modules\CareerProfile\Domain\Exceptions\InvalidResumeIdException;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use PHPUnit\Framework\TestCase;

final class CareerProfileValueObjectsTest extends TestCase
{
    private string $validUuid = '550e8400-e29b-41d4-a716-446655440000';

    public function test_career_profile_id_can_be_generated(): void
    {
        $id = CareerProfileId::generate();
        $this->assertInstanceOf(CareerProfileId::class, $id);
        $this->assertNotEmpty($id->value());
    }

    public function test_career_profile_id_can_be_created_from_string(): void
    {
        $id = CareerProfileId::fromString($this->validUuid);
        $this->assertSame($this->validUuid, $id->value());
    }

    public function test_career_profile_id_throws_exception_for_invalid_format(): void
    {
        $this->expectException(InvalidCareerProfileIdException::class);
        CareerProfileId::fromString('invalid-uuid');
    }

    public function test_career_profile_id_equals(): void
    {
        $a = CareerProfileId::fromString($this->validUuid);
        $b = CareerProfileId::fromString($this->validUuid);
        $c = CareerProfileId::generate();

        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_career_profile_id_of(): void
    {
        $id = CareerProfileId::of($this->validUuid);
        $this->assertSame($this->validUuid, $id->value());
    }

    public function test_career_profile_id_to_string(): void
    {
        $id = CareerProfileId::fromString($this->validUuid);
        $this->assertSame($this->validUuid, (string) $id);
    }

    public function test_career_goal_id_can_be_generated(): void
    {
        $id = CareerGoalId::generate();
        $this->assertInstanceOf(CareerGoalId::class, $id);
        $this->assertNotEmpty($id->value());
    }

    public function test_career_goal_id_can_be_created_from_string(): void
    {
        $id = CareerGoalId::fromString($this->validUuid);
        $this->assertSame($this->validUuid, $id->value());
    }

    public function test_career_goal_id_throws_exception_for_invalid_format(): void
    {
        $this->expectException(InvalidCareerGoalIdException::class);
        CareerGoalId::fromString('bad-uuid');
    }

    public function test_career_goal_id_equals(): void
    {
        $a = CareerGoalId::fromString($this->validUuid);
        $b = CareerGoalId::fromString($this->validUuid);

        $this->assertTrue($a->equals($b));
    }

    public function test_experience_id_can_be_generated(): void
    {
        $id = ExperienceId::generate();
        $this->assertInstanceOf(ExperienceId::class, $id);
    }

    public function test_experience_id_from_string(): void
    {
        $id = ExperienceId::fromString($this->validUuid);
        $this->assertSame($this->validUuid, $id->value());
    }

    public function test_experience_id_throws_exception_for_invalid_format(): void
    {
        $this->expectException(InvalidExperienceIdException::class);
        ExperienceId::fromString('not-a-uuid');
    }

    public function test_experience_id_equals(): void
    {
        $a = ExperienceId::fromString($this->validUuid);
        $b = ExperienceId::fromString($this->validUuid);

        $this->assertTrue($a->equals($b));
    }

    public function test_portfolio_item_id_can_be_generated(): void
    {
        $id = PortfolioItemId::generate();
        $this->assertInstanceOf(PortfolioItemId::class, $id);
    }

    public function test_portfolio_item_id_from_string(): void
    {
        $id = PortfolioItemId::fromString($this->validUuid);
        $this->assertSame($this->validUuid, $id->value());
    }

    public function test_portfolio_item_id_throws_exception_for_invalid_format(): void
    {
        $this->expectException(InvalidPortfolioItemIdException::class);
        PortfolioItemId::fromString('bad');
    }

    public function test_portfolio_item_id_equals(): void
    {
        $a = PortfolioItemId::fromString($this->validUuid);
        $b = PortfolioItemId::fromString($this->validUuid);

        $this->assertTrue($a->equals($b));
    }

    public function test_resume_id_can_be_generated(): void
    {
        $id = ResumeId::generate();
        $this->assertInstanceOf(ResumeId::class, $id);
    }

    public function test_resume_id_from_string(): void
    {
        $id = ResumeId::fromString($this->validUuid);
        $this->assertSame($this->validUuid, $id->value());
    }

    public function test_resume_id_throws_exception_for_invalid_format(): void
    {
        $this->expectException(InvalidResumeIdException::class);
        ResumeId::fromString('bad');
    }

    public function test_resume_id_equals(): void
    {
        $a = ResumeId::fromString($this->validUuid);
        $b = ResumeId::fromString($this->validUuid);

        $this->assertTrue($a->equals($b));
    }

    public function test_resume_id_to_string(): void
    {
        $id = ResumeId::fromString($this->validUuid);
        $this->assertSame($this->validUuid, (string) $id);
    }
}
