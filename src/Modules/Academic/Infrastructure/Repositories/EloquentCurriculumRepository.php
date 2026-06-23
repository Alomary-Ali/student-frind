<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Entities\Curriculum;
use Modules\Academic\Domain\Entities\CurriculumCourse;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use Modules\Academic\Infrastructure\Persistence\EloquentCurriculum;
use Modules\Academic\Infrastructure\Persistence\EloquentCurriculumCourse;
use Ramsey\Uuid\Uuid;

final class EloquentCurriculumRepository implements CurriculumRepositoryInterface
{
    public function findById(CurriculumId $id): ?Curriculum
    {
        $model = EloquentCurriculum::with('courses')->find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function save(Curriculum $curriculum): void
    {
        EloquentCurriculum::updateOrCreate(
            ['id' => $curriculum->id()->value()],
            [
                'name' => $curriculum->name(),
                'code' => $curriculum->code(),
                'description' => $curriculum->description(),
                'total_credits_required' => $curriculum->totalCreditsRequired()->value(),
                'institution_id' => $curriculum->institutionId(),
            ]
        );

        foreach ($curriculum->courses() as $course) {
            EloquentCurriculumCourse::updateOrCreate(
                [
                    'curriculum_id' => $curriculum->id()->value(),
                    'course_id' => $course->courseId()->value(),
                ],
                [
                    'id' => Uuid::uuid4()->toString(),
                    'is_required' => $course->isRequired(),
                    'semester_order' => $course->semesterOrder(),
                ]
            );
        }
    }

    private function toDomain(EloquentCurriculum $model): Curriculum
    {
        $courses = [];
        foreach ($model->courses as $cc) {
            $courses[] = new CurriculumCourse(
                CourseId::fromString($cc->course_id),
                (bool) $cc->is_required,
                (int) $cc->semester_order,
            );
        }

        return Curriculum::reconstitute(
            id: CurriculumId::fromString($model->id),
            name: $model->name,
            code: $model->code,
            description: $model->description,
            totalCreditsRequired: Credits::of((int) $model->total_credits_required),
            institutionId: $model->institution_id,
            createdAt: new DateTimeImmutable($model->created_at->toIso8601String()),
            courses: $courses,
        );
    }
}
