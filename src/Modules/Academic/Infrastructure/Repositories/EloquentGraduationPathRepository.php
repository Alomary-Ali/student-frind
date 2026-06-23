<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\GraduationPathRepositoryInterface;
use Modules\Academic\Domain\Entities\GraduationPath;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Domain\ValueObjects\GraduationPathId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentGraduationPath;

final class EloquentGraduationPathRepository implements GraduationPathRepositoryInterface
{
    public function findByStudentId(StudentId $studentId): ?GraduationPath
    {
        $model = EloquentGraduationPath::where('student_id', $studentId->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function save(GraduationPath $path): void
    {
        EloquentGraduationPath::updateOrCreate(
            ['id' => $path->id()->value()],
            [
                'student_id' => $path->studentId()->value(),
                'curriculum_id' => $path->curriculumId()->value(),
                'credits_earned' => $path->creditsEarned()->value(),
                'credits_required' => $path->creditsRequired()->value(),
                'completion_percentage' => $path->completionPercentage(),
                'is_on_track' => $path->isOnTrack(),
                'estimated_graduation_date' => $path->estimatedGraduationDate()?->format('Y-m-d'),
            ],
        );
    }

    private function toDomain(EloquentGraduationPath $model): GraduationPath
    {
        // Handle invalid credits values by clamping to valid range
        $creditsEarned = (int) $model->credits_earned;
        $creditsRequired = (int) $model->credits_required;

        // Clamp values to valid range (0-30)
        $creditsEarned = max(0, min(30, $creditsEarned));
        $creditsRequired = max(0, min(30, $creditsRequired));

        return GraduationPath::reconstitute(
            id: GraduationPathId::fromString($model->id),
            studentId: StudentId::fromString($model->student_id),
            curriculumId: CurriculumId::fromString($model->curriculum_id),
            creditsEarned: Credits::of($creditsEarned),
            creditsRequired: Credits::of($creditsRequired),
            completionPercentage: (float) $model->completion_percentage,
            isOnTrack: (bool) $model->is_on_track,
            estimatedGraduationDate: $model->estimated_graduation_date
                ? new DateTimeImmutable($model->estimated_graduation_date->format('Y-m-d'))
                : null,
            updatedAt: new DateTimeImmutable($model->updated_at->toIso8601String()),
        );
    }
}
