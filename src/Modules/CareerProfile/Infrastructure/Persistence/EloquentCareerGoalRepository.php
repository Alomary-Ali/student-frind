<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Contracts\CareerGoalRepositoryInterface;
use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentCareerGoal;

final class EloquentCareerGoalRepository implements CareerGoalRepositoryInterface
{
    public function findById(CareerGoalId $id): ?CareerGoal
    {
        $model = EloquentCareerGoal::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(CareerGoal $goal): void
    {
        $model = EloquentCareerGoal::find($goal->id()->value());

        if ($model === null) {
            $model = new EloquentCareerGoal;
            $model->id = $goal->id()->value();
        }

        $model->career_profile_id = $goal->careerProfileId()->value();
        $model->title = $goal->title();
        $model->target_date = $goal->targetDate()->format('Y-m-d H:i:s');
        $model->status = $goal->status()->value;
        $model->progress = $goal->progress();
        $model->save();
    }

    public function delete(CareerGoalId $id): void
    {
        EloquentCareerGoal::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentCareerGoal $model): CareerGoal
    {
        return CareerGoal::reconstitute(
            id: CareerGoalId::of($model->id),
            careerProfileId: CareerProfileId::of($model->career_profile_id),
            title: $model->title,
            targetDate: new DateTimeImmutable($model->target_date->format('Y-m-d H:i:s')),
            status: GoalStatus::from($model->status),
            progress: (int) $model->progress,
        );
    }
}
