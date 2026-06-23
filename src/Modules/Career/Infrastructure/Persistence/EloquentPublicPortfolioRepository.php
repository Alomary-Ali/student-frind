<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Career\Domain\Contracts\PublicPortfolioRepositoryInterface;
use Modules\Career\Domain\Entities\PublicPortfolio;
use Modules\Career\Domain\Enums\PortfolioTheme;
use Modules\Career\Domain\ValueObjects\PortfolioSlug;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;
use Modules\Career\Infrastructure\Persistence\Eloquent\EloquentPublicPortfolio;

final class EloquentPublicPortfolioRepository implements PublicPortfolioRepositoryInterface
{
    public function findById(PublicPortfolioId $id): ?PublicPortfolio
    {
        $model = EloquentPublicPortfolio::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(string $studentId): ?PublicPortfolio
    {
        $model = EloquentPublicPortfolio::where('student_id', $studentId)->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findBySlug(string $slug): ?PublicPortfolio
    {
        $model = EloquentPublicPortfolio::where('slug', $slug)->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(PublicPortfolio $portfolio): void
    {
        $model = EloquentPublicPortfolio::find($portfolio->id()->value());

        if ($model === null) {
            $model = new EloquentPublicPortfolio;
            $model->id = $portfolio->id()->value();
        }

        $model->student_id = $portfolio->studentId();
        $model->slug = $portfolio->slug()->value();
        $model->title = $portfolio->title();
        $model->bio = $portfolio->bio();
        $model->theme = $portfolio->theme()->value;
        $model->is_active = $portfolio->isActive();
        $model->views_count = $portfolio->viewsCount();
        $model->save();
    }

    public function delete(PublicPortfolioId $id): void
    {
        EloquentPublicPortfolio::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentPublicPortfolio $model): PublicPortfolio
    {
        return PublicPortfolio::reconstitute(
            id: PublicPortfolioId::of($model->id),
            studentId: $model->student_id,
            slug: PortfolioSlug::fromString($model->slug),
            title: $model->title,
            bio: $model->bio,
            theme: PortfolioTheme::from($model->theme),
            isActive: (bool) $model->is_active,
            viewsCount: (int) $model->views_count,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
