<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\Events\CareerGoalCompleted;
use Modules\CareerProfile\Domain\Events\CareerGoalCreated;
use Modules\CareerProfile\Domain\Events\CareerProfileCreated;
use Modules\CareerProfile\Domain\Events\ExperienceAdded;
use Modules\CareerProfile\Domain\Events\PortfolioItemAdded;
use Modules\CareerProfile\Domain\Events\ResumeGenerated;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use PHPUnit\Framework\TestCase;

final class CareerProfileEntityTest extends TestCase
{
    private CareerProfileId $profileId;
    private StudentId $studentId;

    protected function setUp(): void
    {
        $this->profileId = CareerProfileId::generate();
        $this->studentId = StudentId::generate();
    }

    public function test_can_create_career_profile(): void
    {
        $profile = CareerProfile::create(
            $this->profileId,
            $this->studentId,
            'علوم الحاسب الآلي',
            'طالب مهتم بتطوير الويب',
            ['AI', 'Web'],
            ['العربية', 'English'],
        );

        $this->assertSame($this->profileId, $profile->id());
        $this->assertSame($this->studentId, $profile->studentId());
        $this->assertSame('علوم الحاسب الآلي', $profile->major());
        $this->assertSame('طالب مهتم بتطوير الويب', $profile->summary());
        $this->assertSame(['AI', 'Web'], $profile->interests());
        $this->assertSame(['العربية', 'English'], $profile->languages());
        $this->assertEmpty($profile->portfolioItems());
        $this->assertEmpty($profile->experiences());
        $this->assertEmpty($profile->resumes());
        $this->assertEmpty($profile->careerGoals());
        $this->assertInstanceOf(DateTimeImmutable::class, $profile->createdAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $profile->updatedAt());
    }

    public function test_create_raises_career_profile_created_event(): void
    {
        $profile = CareerProfile::create(
            $this->profileId,
            $this->studentId,
            'الهندسة',
        );

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CareerProfileCreated::class, $events[0]);
        $this->assertSame($this->profileId->value(), $events[0]->profileId);
        $this->assertSame($this->studentId->value(), $events[0]->studentId);
    }

    public function test_release_events_clears_events(): void
    {
        $profile = CareerProfile::create($this->profileId, $this->studentId, 'الهندسة');
        $profile->releaseEvents();
        $this->assertEmpty($profile->releaseEvents());
    }

    public function test_can_update_profile(): void
    {
        $profile = CareerProfile::create($this->profileId, $this->studentId, 'الهندسة');
        $profile->updateProfile('الذكاء الاصطناعي', 'ملخص جديد', ['ML'], ['English']);

        $this->assertSame('الذكاء الاصطناعي', $profile->major());
        $this->assertSame('ملخص جديد', $profile->summary());
        $this->assertSame(['ML'], $profile->interests());
        $this->assertSame(['English'], $profile->languages());
    }

    public function test_can_add_portfolio_item(): void
    {
        $profile = CareerProfile::create($this->profileId, $this->studentId, 'الهندسة');
        $profile->releaseEvents();

        $itemId = PortfolioItemId::generate();
        $profile->addPortfolioItem(
            $itemId,
            'مشروع اختبار',
            'وصف المشروع',
            'https://example.com',
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            ['Laravel', 'Vue'],
        );

        $this->assertCount(1, $profile->portfolioItems());
        $this->assertSame('مشروع اختبار', $profile->portfolioItems()[0]->title());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(PortfolioItemAdded::class, $events[0]);
    }

    public function test_can_add_experience(): void
    {
        $profile = CareerProfile::create($this->profileId, $this->studentId, 'الهندسة');
        $profile->releaseEvents();

        $expId = ExperienceId::generate();
        $profile->addExperience(
            $expId,
            'شركة اختبار',
            'مطور',
            'وصف الخبرة',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );

        $this->assertCount(1, $profile->experiences());
        $this->assertSame('شركة اختبار', $profile->experiences()[0]->company());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ExperienceAdded::class, $events[0]);
    }

    public function test_can_generate_resume(): void
    {
        $profile = CareerProfile::create($this->profileId, $this->studentId, 'الهندسة');
        $profile->releaseEvents();

        $resumeId = ResumeId::generate();
        $profile->generateResume(
            $resumeId,
            ResumeTemplate::MODERN,
            'محتوى السيرة الذاتية',
        );

        $this->assertCount(1, $profile->resumes());
        $this->assertSame(ResumeTemplate::MODERN, $profile->resumes()[0]->template());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ResumeGenerated::class, $events[0]);
    }

    public function test_can_create_career_goal(): void
    {
        $profile = CareerProfile::create($this->profileId, $this->studentId, 'الهندسة');
        $profile->releaseEvents();

        $goalId = CareerGoalId::generate();
        $profile->createCareerGoal(
            $goalId,
            'تعلم Laravel',
            new DateTimeImmutable('2026-12-31'),
        );

        $this->assertCount(1, $profile->careerGoals());
        $this->assertSame('تعلم Laravel', $profile->careerGoals()[0]->title());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CareerGoalCreated::class, $events[0]);
    }

    public function test_can_update_career_goal_progress_to_completed(): void
    {
        $profile = CareerProfile::create($this->profileId, $this->studentId, 'الهندسة');
        $profile->releaseEvents();

        $goalId = CareerGoalId::generate();
        $profile->createCareerGoal($goalId, 'تعلم Laravel', new DateTimeImmutable('2026-12-31'));
        $profile->releaseEvents();

        $profile->updateCareerGoalProgress($goalId, 100);

        $this->assertSame(100, $profile->careerGoals()[0]->progress());
        $this->assertSame(GoalStatus::COMPLETED, $profile->careerGoals()[0]->status());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CareerGoalCompleted::class, $events[0]);
    }

    public function test_can_reconstitute_profile(): void
    {
        $now = new DateTimeImmutable;
        $profile = CareerProfile::reconstitute(
            $this->profileId,
            $this->studentId,
            'الهندسة',
            'ملخص',
            ['AI'],
            ['English'],
            [],
            [],
            [],
            [],
            $now,
            $now,
        );

        $this->assertSame($this->profileId, $profile->id());
        $this->assertSame($this->studentId, $profile->studentId());
        $this->assertSame('الهندسة', $profile->major());
        $this->assertEmpty($profile->releaseEvents());
    }
}
