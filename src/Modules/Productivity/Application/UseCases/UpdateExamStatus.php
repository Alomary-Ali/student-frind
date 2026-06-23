<?php

declare(strict_types=1);

namespace Modules\Productivity\Application\UseCases;

use Modules\Productivity\Application\DTOs\ExamDto;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\ValueObjects\ExamId;

final readonly class UpdateExamStatus
{
    public function __construct(
        private ExamRepositoryInterface $examRepository,
    ) {}

    public function execute(string $examId, string $status): ExamDto
    {
        $exam = $this->examRepository->findById(
            ExamId::fromString($examId),
        );

        if ($exam === null) {
            throw new \RuntimeException('Exam not found');
        }

        match ($status) {
            'completed' => $exam->markAsCompleted(),
            'cancelled' => $exam->markAsCancelled(),
            default => throw new \InvalidArgumentException('Invalid status'),
        };

        $this->examRepository->save($exam);

        return $this->toDto($exam);
    }

    private function toDto(\Modules\Productivity\Domain\Entities\Exam $exam): ExamDto
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
