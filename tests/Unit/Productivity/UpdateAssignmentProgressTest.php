<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Modules\Productivity\Application\UseCases\UpdateAssignmentProgress;
use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\Entities\Assignment;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class UpdateAssignmentProgressTest extends TestCase
{
    public function test_can_update_assignment_progress(): void
    {
        $assignment = Assignment::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('+7 days'),
        );

        $repository = $this->createMock(AssignmentRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->willReturn($assignment);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Assignment::class));

        $useCase = new UpdateAssignmentProgress($repository);

        $result = $useCase->execute(
            $assignment->id()->value(),
            'in_progress',
        );

        $this->assertInstanceOf(\Modules\Productivity\Application\DTOs\AssignmentDto::class, $result);
        $this->assertEquals('in_progress', $result->status);
    }
}
