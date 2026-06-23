<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Application\DTOs;

use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\DTOs\CareerGoalDto;
use Modules\CareerProfile\Application\DTOs\ExperienceDto;
use Modules\CareerProfile\Application\DTOs\PortfolioItemDto;
use Modules\CareerProfile\Application\DTOs\ResumeDto;
use PHPUnit\Framework\TestCase;

final class CareerProfileDtoTest extends TestCase
{
    public function test_can_create_career_profile_dto(): void
    {
        $dto = new CareerProfileDto(
            id: 'profile-1',
            studentId: 'student-1',
            major: 'علوم الحاسب',
            summary: 'ملخص',
            interests: ['AI', 'Web'],
            languages: ['العربية'],
            portfolioItems: [],
            experiences: [],
            resumes: [],
            careerGoals: [],
        );

        $this->assertSame('profile-1', $dto->id);
        $this->assertSame('student-1', $dto->studentId);
        $this->assertSame('علوم الحاسب', $dto->major);
        $this->assertSame('ملخص', $dto->summary);
        $this->assertSame(['AI', 'Web'], $dto->interests);
        $this->assertSame(['العربية'], $dto->languages);
        $this->assertEmpty($dto->portfolioItems);
        $this->assertEmpty($dto->experiences);
        $this->assertEmpty($dto->resumes);
        $this->assertEmpty($dto->careerGoals);
    }

    public function test_can_create_career_goal_dto(): void
    {
        $dto = new CareerGoalDto(
            id: 'goal-1',
            careerProfileId: 'profile-1',
            title: 'تعلم Laravel',
            targetDate: '2026-12-31',
            status: 'in_progress',
            progress: 50,
        );

        $this->assertSame('goal-1', $dto->id);
        $this->assertSame('profile-1', $dto->careerProfileId);
        $this->assertSame('تعلم Laravel', $dto->title);
        $this->assertSame('2026-12-31', $dto->targetDate);
        $this->assertSame('in_progress', $dto->status);
        $this->assertSame(50, $dto->progress);
    }

    public function test_can_create_experience_dto(): void
    {
        $dto = new ExperienceDto(
            id: 'exp-1',
            careerProfileId: 'profile-1',
            company: 'شركة جوجل',
            position: 'مطور',
            description: 'وصف الخبرة',
            startDate: '2025-01-01',
            endDate: null,
            isCurrent: true,
        );

        $this->assertSame('exp-1', $dto->id);
        $this->assertSame('شركة جوجل', $dto->company);
        $this->assertSame('مطور', $dto->position);
        $this->assertNull($dto->endDate);
        $this->assertTrue($dto->isCurrent);
    }

    public function test_can_create_portfolio_item_dto(): void
    {
        $dto = new PortfolioItemDto(
            id: 'item-1',
            careerProfileId: 'profile-1',
            title: 'مشروع',
            description: 'وصف',
            projectUrl: 'https://example.com',
            githubUrl: null,
            startDate: '2026-01-01',
            endDate: '2026-06-01',
            technologies: ['Laravel', 'Vue'],
        );

        $this->assertSame('item-1', $dto->id);
        $this->assertSame('مشروع', $dto->title);
        $this->assertSame('https://example.com', $dto->projectUrl);
        $this->assertNull($dto->githubUrl);
        $this->assertSame(['Laravel', 'Vue'], $dto->technologies);
    }

    public function test_can_create_resume_dto(): void
    {
        $dto = new ResumeDto(
            id: 'resume-1',
            careerProfileId: 'profile-1',
            template: 'modern',
            content: 'محتوى السيرة الذاتية',
            generatedAt: '2026-06-23 10:00:00',
        );

        $this->assertSame('resume-1', $dto->id);
        $this->assertSame('modern', $dto->template);
        $this->assertSame('محتوى السيرة الذاتية', $dto->content);
        $this->assertSame('2026-06-23 10:00:00', $dto->generatedAt);
    }

    public function test_dtos_are_readonly(): void
    {
        $reflection = new \ReflectionClass(CareerProfileDto::class);
        $this->assertTrue($reflection->isReadOnly());

        $reflection = new \ReflectionClass(CareerGoalDto::class);
        $this->assertTrue($reflection->isReadOnly());
    }
}
