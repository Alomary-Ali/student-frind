<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Modules\Productivity\Application\UseCases\UpdateExamStatus;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\Entities\Exam;
use Modules\Productivity\Domain\Enums\ExamType;
use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class UpdateExamStatusTest extends TestCase
{
    public function test_can_update_exam_status(): void
    {
        $exam = Exam::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: ExamType::MIDTERM,
            examDate: new \DateTimeImmutable('+14 days'),
            location: 'قاعة A',
        );

        $repository = $this->createMock(ExamRepositoryInterface::class);
        $repository->expects($this->once())
            ->method('findById')
            ->willReturn($exam);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Exam::class));

        $useCase = new UpdateExamStatus($repository);

        $result = $useCase->execute(
            $exam->id()->value(),
            'completed'
        );

        $this->assertInstanceOf(\Modules\Productivity\Application\DTOs\ExamDto::class, $result);
        $this->assertEquals('completed', $result->status);
    }
}
