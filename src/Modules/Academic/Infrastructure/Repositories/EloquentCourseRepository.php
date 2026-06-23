<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Illuminate\Support\Facades\Cache;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Infrastructure\Persistence\EloquentCourse;

final class EloquentCourseRepository implements CourseRepositoryInterface
{
    public function findById(CourseId $id): ?Course
    {
        $cacheKey = "course:{$id->value()}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($id) {
            $model = EloquentCourse::find($id->value());

            return $model ? $this->toDomain($model) : null;
        });
    }

    public function findByCode(string $code): ?Course
    {
        $cacheKey = "course:code:{$code}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($code) {
            $model = EloquentCourse::where('code', $code)->first();

            return $model ? $this->toDomain($model) : null;
        });
    }

    public function save(Course $course): void
    {
        EloquentCourse::updateOrCreate(
            ['id' => $course->id()->value()],
            [
                'code' => $course->code(),
                'title' => $course->title(),
                'description' => $course->description(),
                'credit_hours' => $course->creditHours()->value(),
                'is_active' => $course->isActive(),
                'institution_id' => $course->institutionId(),
            ],
        );

        // Invalidate cache
        Cache::forget("course:{$course->id()->value()}");
        Cache::forget("course:code:{$course->code()}");
    }

    public function findAllActive(): array
    {
        return EloquentCourse::where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(fn (EloquentCourse $m) => $this->toDomain($m))
            ->all();
    }

    public function findAllActivePaginated(int $page, int $perPage): object
    {
        $paginator = EloquentCourse::where('is_active', true)
            ->orderBy('code')
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform Eloquent models to Domain entities
        $items = collect($paginator->items())
            ->map(fn (EloquentCourse $m) => $this->toDomain($m))
            ->all();

        $paginator->setCollection(collect($items));

        return $paginator;
    }

    public function findPrerequisites(CourseId $courseId): array
    {
        return \Illuminate\Support\Facades\DB::table('academic_course_prerequisites')
            ->where('course_id', $courseId->value())
            ->get()
            ->map(fn ($row) => [
                'prerequisite_course_id' => $row->prerequisite_course_id,
                'is_required' => (bool) $row->is_required,
                'minimum_grade' => (float) $row->minimum_grade,
            ])
            ->all();
    }

    private function toDomain(EloquentCourse $model): Course
    {
        $createdAt = $model->created_at
            ? new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s'))
            : new DateTimeImmutable;

        return Course::reconstitute(
            id: CourseId::fromString($model->id),
            code: $model->code,
            title: $model->title,
            description: $model->description,
            creditHours: Credits::of((int) $model->credit_hours),
            isActive: (bool) $model->is_active,
            institutionId: $model->institution_id,
            createdAt: $createdAt,
        );
    }
}
