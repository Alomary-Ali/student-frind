<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Application\DTOs;

use Modules\Career\Application\DTOs\CareerPathDto;
use Modules\Career\Application\DTOs\CareerPathStageDto;
use Modules\Career\Application\DTOs\ComprehensiveDashboardDto;
use Modules\Career\Application\DTOs\InterviewAttemptDto;
use Modules\Career\Application\DTOs\InterviewDto;
use Modules\Career\Application\DTOs\InterviewQuestionDto;
use Modules\Career\Application\DTOs\PublicPortfolioDto;
use PHPUnit\Framework\TestCase;

final class CareerDtoTest extends TestCase
{
    public function test_interview_dto(): void
    {
        $dto = new InterviewDto(
            id: 'int-1',
            studentId: 'stu-1',
            type: 'mock',
            status: 'scheduled',
            scheduledAt: '2026-07-15 10:00:00',
        );

        $this->assertSame('int-1', $dto->id);
        $this->assertSame('stu-1', $dto->studentId);
        $this->assertSame('mock', $dto->type);
        $this->assertSame('scheduled', $dto->status);
    }

    public function test_interview_dto_with_score(): void
    {
        $dto = new InterviewDto(
            id: 'int-1',
            studentId: 'stu-1',
            type: 'technical',
            status: 'completed',
            scheduledAt: '2026-07-15 10:00:00',
            questions: [],
            score: 85,
            feedback: 'ممتاز',
        );

        $this->assertSame(85, $dto->score);
        $this->assertSame('ممتاز', $dto->feedback);
    }

    public function test_interview_question_dto(): void
    {
        $dto = new InterviewQuestionDto('q-1', 'int-1', 'ما هو REST?', 'technical', 1);

        $this->assertSame('q-1', $dto->id);
        $this->assertSame('ما هو REST?', $dto->question);
    }

    public function test_interview_attempt_dto(): void
    {
        $dto = new InterviewAttemptDto('a-1', 'int-1', 'stu-1', [['q-1' => 'Answer']], 80);

        $this->assertSame('a-1', $dto->id);
        $this->assertSame(80, $dto->score);
    }

    public function test_career_path_dto(): void
    {
        $dto = new CareerPathDto('p-1', 'مطور ويب', 'وصف', 'Web Developer', ['PHP']);

        $this->assertSame('p-1', $dto->id);
        $this->assertSame('مطور ويب', $dto->title);
        $this->assertSame(['PHP'], $dto->requiredSkills);
    }

    public function test_career_path_stage_dto(): void
    {
        $dto = new CareerPathStageDto('s-1', 'مبتدئ', 1, ['HTML'], 6, '10k');

        $this->assertSame('s-1', $dto->id);
        $this->assertSame(6, $dto->durationMonths);
    }

    public function test_public_portfolio_dto(): void
    {
        $dto = new PublicPortfolioDto('pf-1', 'stu-1', 'my-portfolio', 'معرضي');

        $this->assertSame('pf-1', $dto->id);
        $this->assertSame('my-portfolio', $dto->slug);
        $this->assertFalse($dto->isActive);
    }

    public function test_comprehensive_dashboard_dto(): void
    {
        $dto = new ComprehensiveDashboardDto(
            profile: ['major' => 'CS'],
            readinessScore: 75.5,
            readinessBreakdown: ['gpa' => ['score' => 80]],
        );

        $this->assertSame(['major' => 'CS'], $dto->profile);
        $this->assertSame(75.5, $dto->readinessScore);
    }

    public function test_dtos_are_readonly(): void
    {
        $ref = new \ReflectionClass(InterviewDto::class);
        $this->assertTrue($ref->isReadOnly());

        $ref = new \ReflectionClass(CareerPathDto::class);
        $this->assertTrue($ref->isReadOnly());
    }
}
