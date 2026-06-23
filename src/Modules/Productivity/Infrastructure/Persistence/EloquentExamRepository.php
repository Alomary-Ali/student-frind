<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\Entities\Exam;
use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentExam;
use Modules\Shared\Domain\ValueObjects\UserId;

final class EloquentExamRepository implements ExamRepositoryInterface
{
    public function save(Exam $exam): void
    {
        $model = EloquentExam::updateOrCreate(
            ['id' => $exam->id()->value()],
            $exam->toArray(),
        );
    }

    public function findById(ExamId $id): ?Exam
    {
        $model = EloquentExam::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        $models = EloquentExam::where('user_id', $userId->value())
            ->orderBy('exam_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toDomain($model), $models->toArray());
    }

    public function findUpcomingByUserId(UserId $userId, int $days = 7): array
    {
        $models = EloquentExam::where('user_id', $userId->value())
            ->where('exam_date', '>=', now()->toDateTime())
            ->where('exam_date', '<=', now()->addDays($days)->toDateTime())
            ->where('status', 'scheduled')
            ->orderBy('exam_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toDomain($model), $models->toArray());
    }

    public function delete(ExamId $id): void
    {
        EloquentExam::destroy($id->value());
    }

    private function toDomain(EloquentExam $model): Exam
    {
        return Exam::fromArray($model->toArray());
    }
}
