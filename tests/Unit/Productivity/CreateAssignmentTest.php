<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Illuminate\Support\Facades\Event;
use Modules\Productivity\Application\DTOs\CreateAssignmentDto;
use Modules\Productivity\Application\UseCases\CreateAssignment;
use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\Entities\Assignment;
use Modules\Productivity\Domain\Events\AssignmentCreated;
use Modules\Shared\Domain\ValueObjects\UserId;
use Tests\TestCase;

final class CreateAssignmentTest extends TestCase
{
    public function test_can_create_assignment(): void
    {
        $repository = $this->createMock(AssignmentRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Assignment::class));

        $useCase = new CreateAssignment($repository);

        $dto = new CreateAssignmentDto(
            userId: UserId::generate()->value(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: (new \DateTimeImmutable('+7 days'))->format('Y-m-d H:i:s'),
        );

        $result = $useCase->execute($dto);

        $this->assertInstanceOf(\Modules\Productivity\Application\DTOs\AssignmentDto::class, $result);
        $this->assertEquals('واجب البرمجة', $result->title);
    }

    public function test_dispatches_assignment_created_event(): void
    {
        $repository = $this->createMock(AssignmentRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Assignment::class));

        $useCase = new CreateAssignment($repository);

        $dto = new CreateAssignmentDto(
            userId: UserId::generate()->value(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: (new \DateTimeImmutable('+7 days'))->format('Y-m-d H:i:s'),
        );

        Event::fake([AssignmentCreated::class]);

        $useCase->execute($dto);

        Event::assertDispatched(AssignmentCreated::class);
    }
}
