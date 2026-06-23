<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Skills\Domain\Contracts\LearningPathRepositoryInterface;
use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentLearningPath;

final class EloquentLearningPathRepository implements LearningPathRepositoryInterface
{
    public function findById(LearningPathId $id): ?LearningPath
    {
        $model = EloquentLearningPath::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(StudentId $studentId): array
    {
        $models = EloquentLearningPath::where('student_id', $studentId->value())
            ->orderBy('created_at', 'desc')
            ->get();

        $paths = [];
        foreach ($models as $model) {
            $paths[] = $this->toEntity($model);
        }

        return $paths;
    }

    public function save(LearningPath $learningPath): void
    {
        $model = EloquentLearningPath::find($learningPath->id()->value());

        if ($model === null) {
            $model = new EloquentLearningPath();
            $model->id = $learningPath->id()->value();
        }

        $model->student_id = $learningPath->studentId()->value();
        $model->title = $learningPath->title();
        $model->target_role = $learningPath->targetRole();
        $model->steps = $learningPath->steps();
        $model->progress = $learningPath->progress();
        $model->estimated_completion_date = $learningPath->estimatedCompletionDate()?->format('Y-m-d H:i:s');
        $model->save();
    }

    public function delete(LearningPathId $id): void
    {
        EloquentLearningPath::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentLearningPath $model): LearningPath
    {
        return LearningPath::reconstitute(
            id: LearningPathId::of($model->id),
            studentId: StudentId::of($model->student_id),
            title: $model->title,
            targetRole: $model->target_role,
            steps: $model->steps ?? [],
            progress: (int) $model->progress,
            estimatedCompletionDate: $model->estimated_completion_date ? new DateTimeImmutable($model->estimated_completion_date->format('Y-m-d H:i:s')) : null
        );
    }
}
