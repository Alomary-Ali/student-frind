<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\CreateExamDto;
use Modules\Productivity\Application\DTOs\ExamDto;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\Entities\Exam;
use Modules\Productivity\Domain\Enums\ExamType;
use Modules\Productivity\Domain\Events\ExamCreated;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class CreateExam
{
    public function __construct(
        private ExamRepositoryInterface $examRepository,
    ) {}

    public function execute(CreateExamDto $dto): ExamDto
    {
        $exam = Exam::create(
            userId: UserId::fromString($dto->userId),
            courseId: $dto->courseId,
            title: $dto->title,
            examType: ExamType::from($dto->examType),
            examDate: new \DateTimeImmutable($dto->examDate),
            location: $dto->location,
        );

        $this->examRepository->save($exam);

        event(new ExamCreated(
            examId: $exam->id(),
            userId: $exam->userId(),
            courseId: $exam->courseId(),
            title: $exam->title(),
            examDate: $exam->examDate(),
        ));

        return $this->toDto($exam);
    }

    private function toDto(Exam $exam): ExamDto
    {
        return new ExamDto(
            id: $exam->id()->value(),
            userId: $exam->userId()->value(),
            courseId: $exam->courseId(),
            title: $exam->title(),
            examType: $exam->examType()->value,
            examDate: $exam->examDate()->format('Y-m-d H:i:s'),
            location: $exam->location(),
            status: $exam->status(),
            createdAt: $exam->createdAt()->format('Y-m-d H:i:s'),
            updatedAt: $exam->updatedAt()->format('Y-m-d H:i:s'),
        );
    }
}
