<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\Mappers;

use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\Entities\PortfolioItem;
use Modules\CareerProfile\Domain\Entities\Experience;
use Modules\CareerProfile\Domain\Entities\Resume;
use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\DTOs\PortfolioItemDto;
use Modules\CareerProfile\Application\DTOs\ExperienceDto;
use Modules\CareerProfile\Application\DTOs\ResumeDto;
use Modules\CareerProfile\Application\DTOs\CareerGoalDto;

final class CareerProfileMapper
{
    public function toCareerProfileDto(CareerProfile $profile): CareerProfileDto
    {
        return new CareerProfileDto(
            id: $profile->id()->value(),
            studentId: $profile->studentId()->value(),
            major: $profile->major(),
            summary: $profile->summary(),
            interests: $profile->interests(),
            languages: $profile->languages(),
            portfolioItems: array_map([$this, 'toPortfolioItemDto'], $profile->portfolioItems()),
            experiences: array_map([$this, 'toExperienceDto'], $profile->experiences()),
            resumes: array_map([$this, 'toResumeDto'], $profile->resumes()),
            careerGoals: array_map([$this, 'toCareerGoalDto'], $profile->careerGoals()),
        );
    }

    public function toPortfolioItemDto(PortfolioItem $item): PortfolioItemDto
    {
        return new PortfolioItemDto(
            id: $item->id()->value(),
            careerProfileId: $item->careerProfileId()->value(),
            title: $item->title(),
            description: $item->description(),
            projectUrl: $item->projectUrl(),
            githubUrl: $item->githubUrl(),
            startDate: $item->startDate()->format('Y-m-d'),
            endDate: $item->endDate()?->format('Y-m-d'),
            technologies: $item->technologies(),
        );
    }

    public function toExperienceDto(Experience $exp): ExperienceDto
    {
        return new ExperienceDto(
            id: $exp->id()->value(),
            careerProfileId: $exp->careerProfileId()->value(),
            company: $exp->company(),
            position: $exp->position(),
            description: $exp->description(),
            startDate: $exp->startDate()->format('Y-m-d'),
            endDate: $exp->endDate()?->format('Y-m-d'),
            isCurrent: $exp->isCurrent(),
        );
    }

    public function toResumeDto(Resume $res): ResumeDto
    {
        return new ResumeDto(
            id: $res->id()->value(),
            careerProfileId: $res->careerProfileId()->value(),
            template: $res->template()->value,
            content: $res->content(),
            generatedAt: $res->generatedAt()->format('Y-m-d H:i:s'),
        );
    }

    public function toCareerGoalDto(CareerGoal $goal): CareerGoalDto
    {
        return new CareerGoalDto(
            id: $goal->id()->value(),
            careerProfileId: $goal->careerProfileId()->value(),
            title: $goal->title(),
            targetDate: $goal->targetDate()->format('Y-m-d'),
            status: $goal->status()->value,
            progress: $goal->progress(),
        );
    }
}
