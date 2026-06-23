<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Modules\Productivity\Application\DTOs\CreateExamDto;
use Modules\Productivity\Application\UseCases\CreateExam;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\Entities\Exam;
use Modules\Productivity\Domain\Events\ExamCreated;
use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Shared\Domain\ValueObjects\UserId;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

final class CreateExamTest extends TestCase
{
    public function test_can_create_exam(): void
    {
        $repository = $this->createMock(ExamRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Exam::class));

        $useCase = new CreateExam($repository);

        $dto = new CreateExamDto(
            userId: UserId::generate()->value(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: 'midterm',
            examDate: (new \DateTimeImmutable('+14 days'))->format('Y-m-d H:i:s'),
            location: 'قاعة A',
        );

        $result = $useCase->execute($dto);

        $this->assertInstanceOf(\Modules\Productivity\Application\DTOs\ExamDto::class, $result);
        $this->assertEquals('اختبار منتصف الفصل', $result->title);
    }

    public function test_dispatches_exam_created_event(): void
    {
        $repository = $this->createMock(ExamRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Exam::class));

        $useCase = new CreateExam($repository);

        $dto = new CreateExamDto(
            userId: UserId::generate()->value(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: 'midterm',
            examDate: (new \DateTimeImmutable('+14 days'))->format('Y-m-d H:i:s'),
            location: 'قاعة A',
        );

        Event::fake([ExamCreated::class]);

        $useCase->execute($dto);

        Event::assertDispatched(ExamCreated::class);
    }
}
