<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Services\SkillGapAnalyzer;

final readonly class AnalyzeSkillGap
{
    public function __construct(
        private SkillProfileRepositoryInterface $profiles,
        private SkillGapAnalyzer $analyzer,
    ) {}

    /**
     * @return array<string,mixed>
     */
    public function execute(string $studentId, string $targetRole): array
    {
        $sid = StudentId::of($studentId);
        $profile = $this->profiles->findByStudentId($sid);

        if ($profile === null) {
            throw new \RuntimeException('Skill profile not found. Create a skill profile first.');
        }

        return $this->analyzer->analyze($profile, $targetRole);
    }
}
