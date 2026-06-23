<?php

declare(strict_types=1);

namespace Modules\Academic\Application\UseCases;

use Modules\Academic\Application\DTOs\CreateCourseDto;
use Modules\Academic\Application\DTOs\CourseDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class CreateCourse
{
    public function __construct(
        private CourseRepositoryInterface $courses,
        private EventDispatcherInterface $events,
        private AcademicAuditLoggerInterface $audit,
        private AcademicMapper $mapper,
    ) {}

    public function execute(CreateCourseDto $dto, string $actorUserId): CourseDto
    {
        $course = Course::create(
            id: CourseId::generate(),
            code: $dto->code,
            title: $dto->title,
            description: $dto->description,
            creditHours: Credits::of($dto->creditHours),
            institutionId: $dto->institutionId,
        );

        $this->courses->save($course);
        $this->events->dispatch($course->releaseEvents());

        $this->audit->log(
            actorUserId: $actorUserId,
            action: 'course.created',
            entityType: 'academic_course',
            entityId: $course->id()->value(),
            newValues: ['code' => $dto->code, 'title' => $dto->title],
        );

        return $this->mapper->toCourseDto($course);
    }
}
