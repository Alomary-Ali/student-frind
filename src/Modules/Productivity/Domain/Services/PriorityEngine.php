<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Services;

use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class PriorityEngine
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private AssignmentRepositoryInterface $assignmentRepository,
        private ExamRepositoryInterface $examRepository,
    ) {}

    public function generatePriorityQueue(UserId $userId): array
    {
        $tasks = $this->taskRepository->findByUserId($userId);
        $assignments = $this->assignmentRepository->findByUserId($userId);
        $exams = $this->examRepository->findByUserId($userId);

        $priorityItems = [];

        foreach ($tasks as $task) {
            if (! $task->status()->isCompleted()) {
                $priorityItems[] = [
                    'type' => 'task',
                    'id' => $task->id()->value(),
                    'title' => $task->title(),
                    'priority' => $this->calculateTaskPriority($task),
                    'due_date' => $task->dueDate()?->format('Y-m-d H:i:s'),
                ];
            }
        }

        foreach ($assignments as $assignment) {
            if (! $assignment->status()->isCompleted()) {
                $priorityItems[] = [
                    'type' => 'assignment',
                    'id' => $assignment->id()->value(),
                    'title' => $assignment->title(),
                    'priority' => $this->calculateAssignmentPriority($assignment),
                    'due_date' => $assignment->dueDate()->format('Y-m-d H:i:s'),
                ];
            }
        }

        foreach ($exams as $exam) {
            if ($exam->isUpcoming()) {
                $priorityItems[] = [
                    'type' => 'exam',
                    'id' => $exam->id()->value(),
                    'title' => $exam->title(),
                    'priority' => $this->calculateExamPriority($exam),
                    'due_date' => $exam->examDate()->format('Y-m-d H:i:s'),
                ];
            }
        }

        usort($priorityItems, fn ($a, $b) => $b['priority'] <=> $a['priority']);

        return $priorityItems;
    }

    private function calculateTaskPriority($task): int
    {
        $priority = $task->priority()->weight() * 10;

        if ($task->dueDate()) {
            $daysUntilDue = (new \DateTime)->diff($task->dueDate())->days;
            if ($daysUntilDue <= 1) {
                $priority += 50;
            } elseif ($daysUntilDue <= 3) {
                $priority += 30;
            } elseif ($daysUntilDue <= 7) {
                $priority += 15;
            }
        }

        if ($task->dueDate() && $task->dueDate() < new \DateTime) {
            $priority += 100;
        }

        return $priority;
    }

    private function calculateAssignmentPriority($assignment): int
    {
        $priority = 40;

        $daysUntilDue = (new \DateTime)->diff($assignment->dueDate())->days;
        if ($daysUntilDue <= 1) {
            $priority += 50;
        } elseif ($daysUntilDue <= 3) {
            $priority += 30;
        } elseif ($daysUntilDue <= 7) {
            $priority += 15;
        }

        if ($assignment->isOverdue()) {
            $priority += 100;
        }

        return $priority;
    }

    private function calculateExamPriority($exam): int
    {
        $priority = 50;

        $daysUntilExam = (new \DateTime)->diff($exam->examDate())->days;
        if ($daysUntilExam <= 1) {
            $priority += 50;
        } elseif ($daysUntilExam <= 3) {
            $priority += 30;
        } elseif ($daysUntilExam <= 7) {
            $priority += 15;
        }

        $priority += $exam->examType()->weight() * 10;

        return $priority;
    }
}
