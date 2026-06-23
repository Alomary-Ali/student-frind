<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Contracts;

interface AiCareerServiceInterface
{
    public function generateAdvice(string $studentId, string $query): string;

    public function reviewResume(string $resumeContent): array;

    public function generateInterviewQuestions(string $role, string $type): array;

    public function analyzeSkillGap(array $skills, string $targetRole): array;

    public function matchOpportunities(string $studentId): array;
}
