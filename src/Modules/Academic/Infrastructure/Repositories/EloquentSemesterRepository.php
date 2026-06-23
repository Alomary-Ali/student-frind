<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Illuminate\Support\Facades\Cache;
use Modules\Academic\Domain\Contracts\SemesterRepositoryInterface;
use Modules\Academic\Domain\Entities\Semester;
use Modules\Academic\Domain\ValueObjects\SemesterId;
use Modules\Academic\Infrastructure\Persistence\EloquentSemester;

final class EloquentSemesterRepository implements SemesterRepositoryInterface
{
    public function findById(SemesterId $id): ?Semester
    {
        $cacheKey = "semester:{$id->value()}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($id) {
            $model = EloquentSemester::find($id->value());

            return $model ? $this->toDomain($model) : null;
        });
    }

    public function save(Semester $semester): void
    {
        EloquentSemester::updateOrCreate(
            ['id' => $semester->id()->value()],
            [
                'name' => $semester->name(),
                'name_en' => $semester->name(),
                'code' => $semester->code(),
                'start_date' => $semester->startDate()->format('Y-m-d'),
                'end_date' => $semester->endDate()->format('Y-m-d'),
                'is_active' => $semester->isActive(),
                'institution_id' => $semester->institutionId(),
            ]
        );

        // Invalidate cache
        Cache::forget("semester:{$semester->id()->value()}");
    }

    public function findAllActive(): array
    {
        return EloquentSemester::where('is_active', true)->orderBy('start_date', 'desc')
            ->get()->map(fn ($m) => $this->toDomain($m))->all();
    }

    private function toDomain(EloquentSemester $model): Semester
    {
        $createdAt = $model->created_at
            ? new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s'))
            : new DateTimeImmutable();

        return Semester::reconstitute(
            id: SemesterId::fromString($model->id),
            name: $model->name,
            code: $model->code,
            startDate: new DateTimeImmutable($model->start_date->format('Y-m-d')),
            endDate: new DateTimeImmutable($model->end_date->format('Y-m-d')),
            isActive: (bool) $model->is_active,
            institutionId: $model->institution_id,
            createdAt: $createdAt,
        );
    }
}
