<?php

declare(strict_types=1);

namespace Modules\Productivity\Infrastructure\Persistence;

use Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface;
use Modules\Productivity\Domain\Entities\Project;
use Modules\Productivity\Domain\ValueObjects\ProjectId;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentProject;
use Modules\Shared\Domain\ValueObjects\UserId;

final class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function save(Project $project): void
    {
        $model = EloquentProject::updateOrCreate(
            ['id' => $project->id()->value()],
            $project->toArray(),
        );
    }

    public function findById(ProjectId $id): ?Project
    {
        $model = EloquentProject::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        $models = EloquentProject::where('user_id', $userId->value())
            ->orderBy('due_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toDomain($model), $models->toArray());
    }

    public function findActiveByUserId(UserId $userId): array
    {
        $models = EloquentProject::where('user_id', $userId->value())
            ->whereIn('status', ['planning', 'in_progress'])
            ->orderBy('due_date', 'asc')
            ->get();

        return array_map(fn ($model) => $this->toDomain($model), $models->toArray());
    }

    public function delete(ProjectId $id): void
    {
        EloquentProject::destroy($id->value());
    }

    private function toDomain(EloquentProject $model): Project
    {
        return Project::fromArray($model->toArray());
    }
}
