<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Contracts\FaqRepositoryInterface;
use Modules\StudentServices\Domain\Entities\FAQ;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentFaqItem;

final class EloquentFaqRepository implements FaqRepositoryInterface
{
    public function findAll(): array
    {
        return EloquentFaqItem::orderBy('sort_order')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByCategory(string $categoryId): array
    {
        return EloquentFaqItem::where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(FAQ $faq): void
    {
        $model = EloquentFaqItem::find($faq->id());

        if ($model === null) {
            $model = new EloquentFaqItem;
            $model->id = $faq->id();
        }

        $model->category_id = $faq->categoryId();
        $model->question = $faq->question();
        $model->answer = $faq->answer();
        $model->sort_order = $faq->sortOrder();
        $model->is_active = $faq->isActive();
        $model->save();
    }

    public function delete(string $id): void
    {
        EloquentFaqItem::where('id', $id)->delete();
    }

    private function toEntity(EloquentFaqItem $model): FAQ
    {
        return FAQ::reconstitute(
            id: $model->id,
            categoryId: $model->category_id,
            question: $model->question,
            answer: $model->answer,
            sortOrder: (int) $model->sort_order,
            isActive: (bool) $model->is_active,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
