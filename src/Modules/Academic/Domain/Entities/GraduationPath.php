<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\GraduationPathId;
use Modules\Academic\Domain\ValueObjects\StudentId;

final class GraduationPath
{
    private function __construct(
        private readonly GraduationPathId $id,
        private readonly StudentId $studentId,
        private readonly CurriculumId $curriculumId,
        private Credits $creditsEarned,
        private readonly Credits $creditsRequired,
        private float $completionPercentage,
        private bool $isOnTrack,
        private readonly ?DateTimeImmutable $estimatedGraduationDate,
        private readonly DateTimeImmutable $updatedAt,
    ) {}

    public static function initialize(
        GraduationPathId $id,
        StudentId $studentId,
        CurriculumId $curriculumId,
        Credits $creditsRequired,
        ?DateTimeImmutable $estimatedGraduationDate = null,
    ): self {
        return new self(
            id: $id,
            studentId: $studentId,
            curriculumId: $curriculumId,
            creditsEarned: Credits::of(0),
            creditsRequired: $creditsRequired,
            completionPercentage: 0.0,
            isOnTrack: true,
            estimatedGraduationDate: $estimatedGraduationDate,
            updatedAt: new DateTimeImmutable(),
        );
    }

    public static function reconstitute(
        GraduationPathId $id,
        StudentId $studentId,
        CurriculumId $curriculumId,
        Credits $creditsEarned,
        Credits $creditsRequired,
        float $completionPercentage,
        bool $isOnTrack,
        ?DateTimeImmutable $estimatedGraduationDate,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id, $studentId, $curriculumId, $creditsEarned, $creditsRequired,
            $completionPercentage, $isOnTrack, $estimatedGraduationDate, $updatedAt,
        );
    }

    public function updateProgress(Credits $creditsEarned, float $currentGpa): void
    {
        $this->creditsEarned = $creditsEarned;
        $required = $this->creditsRequired->value();

        $this->completionPercentage = $required > 0
            ? round(($creditsEarned->value() / $required) * 100, 2)
            : 0.0;

        $this->isOnTrack = $this->completionPercentage >= 50.0 && $currentGpa >= 2.0;
    }

    public function id(): GraduationPathId { return $this->id; }
    public function studentId(): StudentId { return $this->studentId; }
    public function curriculumId(): CurriculumId { return $this->curriculumId; }
    public function creditsEarned(): Credits { return $this->creditsEarned; }
    public function creditsRequired(): Credits { return $this->creditsRequired; }
    public function completionPercentage(): float { return $this->completionPercentage; }
    public function isOnTrack(): bool { return $this->isOnTrack; }
    public function estimatedGraduationDate(): ?DateTimeImmutable { return $this->estimatedGraduationDate; }
    public function updatedAt(): DateTimeImmutable { return $this->updatedAt; }
}
