<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Contracts\ResumeRepositoryInterface;
use Modules\CareerProfile\Domain\Entities\Resume;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentResume;

final class EloquentResumeRepository implements ResumeRepositoryInterface
{
    public function findById(ResumeId $id): ?Resume
    {
        $model = EloquentResume::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(Resume $resume): void
    {
        $model = EloquentResume::find($resume->id()->value());

        if ($model === null) {
            $model = new EloquentResume;
            $model->id = $resume->id()->value();
        }

        $model->career_profile_id = $resume->careerProfileId()->value();
        $model->template = $resume->template()->value;
        $model->content = $resume->content();
        $model->generated_at = $resume->generatedAt()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function delete(ResumeId $id): void
    {
        EloquentResume::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentResume $model): Resume
    {
        return Resume::reconstitute(
            id: ResumeId::of($model->id),
            careerProfileId: CareerProfileId::of($model->career_profile_id),
            template: ResumeTemplate::from($model->template),
            content: $model->content,
            generatedAt: new DateTimeImmutable($model->generated_at->format('Y-m-d H:i:s')),
        );
    }
}
