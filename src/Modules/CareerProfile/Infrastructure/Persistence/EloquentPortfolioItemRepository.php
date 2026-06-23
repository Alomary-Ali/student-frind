<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Contracts\PortfolioItemRepositoryInterface;
use Modules\CareerProfile\Domain\Entities\PortfolioItem;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentPortfolioItem;

final class EloquentPortfolioItemRepository implements PortfolioItemRepositoryInterface
{
    public function findById(PortfolioItemId $id): ?PortfolioItem
    {
        $model = EloquentPortfolioItem::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(PortfolioItem $item): void
    {
        $model = EloquentPortfolioItem::find($item->id()->value());

        if ($model === null) {
            $model = new EloquentPortfolioItem();
            $model->id = $item->id()->value();
        }

        $model->career_profile_id = $item->careerProfileId()->value();
        $model->title = $item->title();
        $model->description = $item->description();
        $model->project_url = $item->projectUrl();
        $model->github_url = $item->githubUrl();
        $model->start_date = $item->startDate()->format('Y-m-d H:i:s');
        $model->end_date = $item->endDate()?->format('Y-m-d H:i:s');
        $model->technologies = $item->technologies();
        $model->save();
    }

    public function delete(PortfolioItemId $id): void
    {
        EloquentPortfolioItem::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentPortfolioItem $model): PortfolioItem
    {
        return PortfolioItem::reconstitute(
            id: PortfolioItemId::of($model->id),
            careerProfileId: CareerProfileId::of($model->career_profile_id),
            title: $model->title,
            description: $model->description,
            projectUrl: $model->project_url,
            githubUrl: $model->github_url,
            startDate: new DateTimeImmutable($model->start_date->format('Y-m-d H:i:s')),
            endDate: $model->end_date ? new DateTimeImmutable($model->end_date->format('Y-m-d H:i:s')) : null,
            technologies: $model->technologies ?? []
        );
    }
}
