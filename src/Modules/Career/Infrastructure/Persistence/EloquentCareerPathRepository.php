<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\Entities\CareerPath;
use Modules\Career\Domain\Entities\CareerPathStage;
use Modules\Career\Domain\ValueObjects\CareerPathId;
use Modules\Career\Domain\ValueObjects\CareerPathStageId;
use Modules\Career\Infrastructure\Persistence\Eloquent\EloquentCareerPath;
use Modules\Career\Infrastructure\Persistence\Eloquent\EloquentCareerPathStage;

final class EloquentCareerPathRepository implements CareerPathRepositoryInterface
{
    public function findById(CareerPathId $id): ?CareerPath
    {
        $model = EloquentCareerPath::with('stages')->find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findAll(): array
    {
        return EloquentCareerPath::with('stages')
            ->orderBy('title')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByTargetRole(string $targetRole): array
    {
        return EloquentCareerPath::with('stages')
            ->where('target_role', 'like', "%{$targetRole}%")
            ->orderBy('title')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(CareerPath $careerPath): void
    {
        $model = EloquentCareerPath::find($careerPath->id()->value());

        if ($model === null) {
            $model = new EloquentCareerPath;
            $model->id = $careerPath->id()->value();
        }

        $model->title = $careerPath->title();
        $model->description = $careerPath->description();
        $model->target_role = $careerPath->targetRole();
        $model->required_skills = $careerPath->requiredSkills();
        $model->average_salary = $careerPath->averageSalary();
        $model->growth_rate = $careerPath->growthRate();
        $model->save();

        // Sync stages
        $currentStageIds = array_map(
            fn ($stage) => $stage->id()->value(),
            $careerPath->stages(),
        );

        EloquentCareerPathStage::where('career_path_id', $careerPath->id()->value())
            ->whereNotIn('id', $currentStageIds)
            ->delete();

        foreach ($careerPath->stages() as $stage) {
            $sModel = EloquentCareerPathStage::find($stage->id()->value()) ?? new EloquentCareerPathStage;
            $sModel->id = $stage->id()->value();
            $sModel->career_path_id = $careerPath->id()->value();
            $sModel->title = $stage->title();
            $sModel->order = $stage->order();
            $sModel->required_skills = $stage->requiredSkills();
            $sModel->duration_months = $stage->durationMonths();
            $sModel->salary_range = $stage->salaryRange();
            $sModel->description = $stage->description();
            $sModel->save();
        }
    }

    public function delete(CareerPathId $id): void
    {
        EloquentCareerPath::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentCareerPath $model): CareerPath
    {
        $stages = [];
        foreach ($model->stages as $stage) {
            $stages[] = CareerPathStage::reconstitute(
                id: CareerPathStageId::of($stage->id),
                title: $stage->title,
                order: (int) $stage->order,
                requiredSkills: $stage->required_skills ?? [],
                durationMonths: (int) $stage->duration_months,
                salaryRange: $stage->salary_range,
                description: $stage->description,
            );
        }

        return CareerPath::reconstitute(
            id: CareerPathId::of($model->id),
            title: $model->title,
            description: $model->description,
            targetRole: $model->target_role,
            requiredSkills: $model->required_skills ?? [],
            stages: $stages,
            averageSalary: $model->average_salary,
            growthRate: $model->growth_rate,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
