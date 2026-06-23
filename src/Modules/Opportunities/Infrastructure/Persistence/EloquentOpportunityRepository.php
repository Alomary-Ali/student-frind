<?php

declare(strict_types=1);

namespace Modules\Opportunities\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\Enums\OpportunityStatus;
use Modules\Opportunities\Domain\Enums\OpportunityType;
use Modules\Opportunities\Domain\Enums\Provider;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentOpportunity;

final class EloquentOpportunityRepository implements OpportunityRepositoryInterface
{
    public function findById(OpportunityId $id): ?Opportunity
    {
        $model = EloquentOpportunity::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function findByType(OpportunityType $type): array
    {
        $models = EloquentOpportunity::where('type', $type->value)->get();

        return $models->map(fn (EloquentOpportunity $model) => $this->toEntity($model))->toArray();
    }

    public function findAll(): array
    {
        $models = EloquentOpportunity::orderBy('created_at', 'desc')->get();

        return $models->map(fn (EloquentOpportunity $model) => $this->toEntity($model))->toArray();
    }

    public function search(string $query): array
    {
        $models = EloquentOpportunity::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->get();

        return $models->map(fn (EloquentOpportunity $model) => $this->toEntity($model))->toArray();
    }

    public function save(Opportunity $opportunity): void
    {
        $model = EloquentOpportunity::find($opportunity->id()->value());

        if ($model === null) {
            $model = new EloquentOpportunity;
            $model->id = $opportunity->id()->value();
        }

        $model->title = $opportunity->title();
        $model->description = $opportunity->description();
        $model->provider = $opportunity->provider()->value;
        $model->type = $opportunity->type()->value;
        $model->location = $opportunity->location();
        $model->country = $opportunity->country();
        $model->deadline = $opportunity->deadline()?->format('Y-m-d H:i:s');
        $model->apply_url = $opportunity->applyUrl();
        $model->status = $opportunity->status()->value;
        $model->metadata = $opportunity->metadata();
        $model->source_url = $opportunity->sourceUrl();
        $model->image_url = $opportunity->imageUrl();
        $model->tags = $opportunity->tags();
        $model->save();
    }

    public function delete(OpportunityId $id): void
    {
        EloquentOpportunity::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentOpportunity $model): Opportunity
    {
        return Opportunity::reconstitute(
            id: OpportunityId::of($model->id),
            title: $model->title,
            description: $model->description ?? '',
            provider: Provider::from($model->provider),
            type: OpportunityType::from($model->type),
            location: $model->location,
            country: $model->country,
            deadline: $model->deadline ? new DateTimeImmutable($model->deadline->format('Y-m-d H:i:s')) : null,
            applyUrl: $model->apply_url,
            status: OpportunityStatus::from($model->status),
            metadata: $model->metadata ?? [],
            sourceUrl: $model->source_url,
            imageUrl: $model->image_url,
            tags: $model->tags ?? [],
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
