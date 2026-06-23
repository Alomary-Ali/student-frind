<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Application\UseCases;

use Modules\Academic\Application\DTOs\CreateCourseDto;
use Modules\Academic\Application\DTOs\CourseDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Application\UseCases\CreateCourse;
use Modules\Academic\Domain\Contracts\AcademicAuditLoggerInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Entities\Course;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class CreateCourseUseCaseTest extends TestCase
{
    private CourseRepositoryInterface $courseRepository;
    private EventDispatcherInterface $eventDispatcher;
    private AcademicAuditLoggerInterface $auditLogger;
    private AcademicMapper $mapper;
    private CreateCourse $useCase;

    protected function setUp(): void
    {
        $this->courseRepository = $this->createMock(CourseRepositoryInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->auditLogger = $this->createMock(AcademicAuditLoggerInterface::class);
        $this->mapper = new AcademicMapper();

        $this->useCase = new CreateCourse(
            courses: $this->courseRepository,
            events: $this->eventDispatcher,
            audit: $this->auditLogger,
            mapper: $this->mapper,
        );
    }

    public function test_creates_course_successfully(): void
    {
        $actorUserId = '550e8400-e29b-41d4-a716-446655440001';
        $dto = new CreateCourseDto(
            code: 'CS101',
            title: 'Introduction to Computer Science',
            description: 'Basic concepts of computer science',
            creditHours: 3,
            institutionId: 'inst-uuid',
        );

        $this->courseRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Course::class));

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isType('array'));

        $this->auditLogger->expects($this->once())
            ->method('log')
            ->with(
                $actorUserId,
                'course.created',
                'academic_course',
                $this->isType('string'),
                null,
                $this->callback(fn (array $newValues) => $newValues['code'] === 'CS101'),
            );

        $result = $this->useCase->execute($dto, $actorUserId);

        $this->assertInstanceOf(CourseDto::class, $result);
        $this->assertSame('CS101', $result->code);
        $this->assertSame('Introduction to Computer Science', $result->title);
        $this->assertSame(3, $result->creditHours);
        $this->assertTrue($result->isActive);
    }

    public function test_creates_course_without_institution(): void
    {
        $dto = new CreateCourseDto(
            code: 'MATH201',
            title: 'Calculus II',
            description: 'Advanced calculus',
            creditHours: 4,
        );

        $this->courseRepository->expects($this->once())
            ->method('save');

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch');

        $result = $this->useCase->execute($dto, 'actor-uuid');

        $this->assertSame('MATH201', $result->code);
        $this->assertNull($result->institutionId);
    }
}
