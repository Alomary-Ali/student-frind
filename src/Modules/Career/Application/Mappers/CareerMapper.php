<?php

declare(strict_types=1);

namespace Modules\Career\Application\Mappers;

use Modules\Career\Application\DTOs\CareerPathDto;
use Modules\Career\Application\DTOs\CareerPathStageDto;
use Modules\Career\Application\DTOs\ComprehensiveDashboardDto;
use Modules\Career\Application\DTOs\InterviewAttemptDto;
use Modules\Career\Application\DTOs\InterviewDto;
use Modules\Career\Application\DTOs\InterviewQuestionDto;
use Modules\Career\Application\DTOs\PublicPortfolioDto;
use Modules\Career\Domain\Entities\CareerPath;
use Modules\Career\Domain\Entities\CareerPathStage;
use Modules\Career\Domain\Entities\Interview;
use Modules\Career\Domain\Entities\PublicPortfolio;

final class CareerMapper
{
    public function toInterviewDto(Interview $interview): InterviewDto
    {
        return new InterviewDto(
            id: $interview->id()->value(),
            studentId: $interview->studentId(),
            type: $interview->type()->value,
            status: $interview->status()->value,
            scheduledAt: $interview->scheduledAt()->format('Y-m-d H:i:s'),
            questions: $interview->questions(),
            score: $interview->score(),
            feedback: $interview->feedback(),
        );
    }

    public function toInterviewQuestionDto(array $question): InterviewQuestionDto
    {
        return new InterviewQuestionDto(
            id: $question['id'] ?? '',
            interviewId: $question['interview_id'] ?? '',
            question: $question['question'] ?? '',
            category: $question['category'] ?? null,
            order: (int) ($question['order'] ?? 0),
        );
    }

    public function toInterviewAttemptDto(array $data): InterviewAttemptDto
    {
        return new InterviewAttemptDto(
            id: $data['id'] ?? '',
            interviewId: $data['interview_id'] ?? '',
            studentId: $data['student_id'] ?? '',
            answers: $data['answers'] ?? [],
            score: $data['score'] ?? null,
            feedback: $data['feedback'] ?? null,
            submittedAt: $data['submitted_at'] ?? '',
        );
    }

    public function toCareerPathDto(CareerPath $path): CareerPathDto
    {
        return new CareerPathDto(
            id: $path->id()->value(),
            title: $path->title(),
            description: $path->description(),
            targetRole: $path->targetRole(),
            requiredSkills: $path->requiredSkills(),
            stages: array_map(fn (CareerPathStage $stage) => $this->toCareerPathStageDto($stage), $path->stages()),
            averageSalary: $path->averageSalary(),
            growthRate: $path->growthRate(),
            totalDuration: $path->getTotalDuration(),
        );
    }

    public function toCareerPathStageDto(CareerPathStage $stage): CareerPathStageDto
    {
        return new CareerPathStageDto(
            id: $stage->id()->value(),
            title: $stage->title(),
            order: $stage->order(),
            requiredSkills: $stage->requiredSkills(),
            durationMonths: $stage->durationMonths(),
            salaryRange: $stage->salaryRange(),
            description: $stage->description(),
        );
    }

    public function toPublicPortfolioDto(PublicPortfolio $portfolio): PublicPortfolioDto
    {
        return new PublicPortfolioDto(
            id: $portfolio->id()->value(),
            studentId: $portfolio->studentId(),
            slug: $portfolio->slug()->value(),
            title: $portfolio->title(),
            bio: $portfolio->bio(),
            theme: $portfolio->theme()->value,
            isActive: $portfolio->isActive(),
            viewsCount: $portfolio->viewsCount(),
        );
    }

    public function toComprehensiveDashboardDto(
        ?array $profile,
        ?array $skillProfile,
        array $opportunities,
        array $interviews,
        array $careerPaths,
        float $readinessScore,
        array $readinessBreakdown,
    ): ComprehensiveDashboardDto {
        return new ComprehensiveDashboardDto(
            profile: $profile,
            skillProfile: $skillProfile,
            opportunities: $opportunities,
            interviews: $interviews,
            careerPaths: $careerPaths,
            readinessScore: $readinessScore,
            readinessBreakdown: $readinessBreakdown,
        );
    }
}
