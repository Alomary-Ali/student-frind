<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Contracts\ExperienceRepositoryInterface;
use Modules\CareerProfile\Domain\Entities\Experience;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentExperience;

final class EloquentExperienceRepository implements ExperienceRepositoryInterface
{
    public function findById(ExperienceId $id): ?Experience
    {
        $model = EloquentExperience::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(Experience $experience): void
    {
        $model = EloquentExperience::find($experience->id()->value());

        if ($model === null) {
            $model = new EloquentExperience;
            $model->id = $experience->id()->value();
        }

        $model->career_profile_id = $experience->careerProfileId()->value();
        $model->company = $experience->company();
        $model->position = $experience->position();
        $model->description = $experience->description();
        $model->start_date = $experience->startDate()->format('Y-m-d H:i:s');
        $model->end_date = $experience->endDate()?->format('Y-m-d H:i:s');
        $model->is_current = $experience->isCurrent();
        $model->save();
    }

    public function delete(ExperienceId $id): void
    {
        EloquentExperience::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentExperience $model): Experience
    {
        return Experience::reconstitute(
            id: ExperienceId::of($model->id),
            careerProfileId: CareerProfileId::of($model->career_profile_id),
            company: $model->company,
            position: $model->position,
            description: $model->description,
            startDate: new DateTimeImmutable($model->start_date->format('Y-m-d H:i:s')),
            endDate: $model->end_date ? new DateTimeImmutable($model->end_date->format('Y-m-d H:i:s')) : null,
            isCurrent: (bool) $model->is_current,
        );
    }
}
