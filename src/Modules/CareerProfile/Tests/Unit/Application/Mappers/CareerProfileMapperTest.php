<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Application\Mappers;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Domain\Entities\Experience;
use Modules\CareerProfile\Domain\Entities\PortfolioItem;
use Modules\CareerProfile\Domain\Entities\Resume;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use PHPUnit\Framework\TestCase;

final class CareerProfileMapperTest extends TestCase
{
    private CareerProfileMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new CareerProfileMapper();
    }

    public function test_to_career_profile_dto(): void
    {
        $profileId = CareerProfileId::generate();
        $studentId = StudentId::generate();

        $profile = CareerProfile::create(
            $profileId,
            $studentId,
            'علوم الحاسب',
            'ملخص مهني',
            ['AI'],
            ['العربية'],
        );

        $dto = $this->mapper->toCareerProfileDto($profile);

        $this->assertSame($profileId->value(), $dto->id);
        $this->assertSame($studentId->value(), $dto->studentId);
        $this->assertSame('علوم الحاسب', $dto->major);
        $this->assertSame('ملخص مهني', $dto->summary);
        $this->assertSame(['AI'], $dto->interests);
        $this->assertSame(['العربية'], $dto->languages);
    }

    public function test_to_portfolio_item_dto(): void
    {
        $item = PortfolioItem::create(
            PortfolioItemId::generate(),
            CareerProfileId::generate(),
            'مشروع',
            'وصف',
            'https://example.com',
            'https://github.com/test',
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-06-01'),
            ['Laravel'],
        );

        $dto = $this->mapper->toPortfolioItemDto($item);

        $this->assertSame('مشروع', $dto->title);
        $this->assertSame('2026-01-01', $dto->startDate);
        $this->assertSame('2026-06-01', $dto->endDate);
        $this->assertSame(['Laravel'], $dto->technologies);
    }

    public function test_to_portfolio_item_dto_with_null_optionals(): void
    {
        $item = PortfolioItem::create(
            PortfolioItemId::generate(),
            CareerProfileId::generate(),
            'مشروع',
            'وصف',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            [],
        );

        $dto = $this->mapper->toPortfolioItemDto($item);

        $this->assertNull($dto->projectUrl);
        $this->assertNull($dto->githubUrl);
        $this->assertNull($dto->endDate);
    }

    public function test_to_experience_dto(): void
    {
        $exp = Experience::create(
            ExperienceId::generate(),
            CareerProfileId::generate(),
            'شركة',
            'مطور',
            'وصف',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );

        $dto = $this->mapper->toExperienceDto($exp);

        $this->assertSame('شركة', $dto->company);
        $this->assertSame('مطور', $dto->position);
        $this->assertNull($dto->endDate);
        $this->assertTrue($dto->isCurrent);
    }

    public function test_to_resume_dto(): void
    {
        $resume = Resume::create(
            ResumeId::generate(),
            CareerProfileId::generate(),
            ResumeTemplate::MODERN,
            'محتوى',
        );

        $dto = $this->mapper->toResumeDto($resume);

        $this->assertSame('modern', $dto->template);
        $this->assertSame('محتوى', $dto->content);
    }

    public function test_to_career_goal_dto(): void
    {
        $goal = CareerGoal::create(
            CareerGoalId::generate(),
            CareerProfileId::generate(),
            'هدف اختبار',
            new DateTimeImmutable('2026-12-31'),
        );

        $dto = $this->mapper->toCareerGoalDto($goal);

        $this->assertSame('هدف اختبار', $dto->title);
        $this->assertSame('2026-12-31', $dto->targetDate);
        $this->assertSame('not_started', $dto->status);
        $this->assertSame(0, $dto->progress);
    }

    public function test_to_career_profile_dto_with_related_entities(): void
    {
        $profileId = CareerProfileId::generate();
        $profile = CareerProfile::create($profileId, StudentId::generate(), 'الهندسة');

        $profile->addExperience(
            ExperienceId::generate(),
            'شركة',
            'وظيفة',
            'وصف',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );

        $profile->addPortfolioItem(
            PortfolioItemId::generate(),
            'مشروع',
            'وصف',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            [],
        );

        $profile->generateResume(
            ResumeId::generate(),
            ResumeTemplate::MODERN,
            'سيرة ذاتية',
        );

        $dto = $this->mapper->toCareerProfileDto($profile);

        $this->assertCount(1, $dto->experiences);
        $this->assertCount(1, $dto->portfolioItems);
        $this->assertCount(1, $dto->resumes);
    }
}
