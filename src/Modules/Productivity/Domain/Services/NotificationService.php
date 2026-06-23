<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Services;

use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\Enums\NotificationType;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\ValueObjects\ReminderId;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class NotificationService
{
    public function __construct(
        private ReminderRepositoryInterface $reminderRepository,
        private TaskRepositoryInterface $taskRepository,
        private AssignmentRepositoryInterface $assignmentRepository,
        private ExamRepositoryInterface $examRepository,
    ) {}

    public function generateTaskDueReminder(UserId $userId): void
    {
        $tasks = $this->taskRepository->findDueSoonByUserId($userId, 1);

        foreach ($tasks as $task) {
            $reminder = Reminder::create(
                userId: $userId,
                message: "المهمة '{$task->title()}' موعد غداً",
                triggerAt: $task->dueDate()->modify('-1 day')->format('Y-m-d H:i:s'),
                type: ReminderType::IN_APP,
                relatedEntityType: 'task',
                relatedEntityId: $task->id()->value(),
            );

            $this->reminderRepository->save($reminder);
        }
    }

    public function generateAssignmentDueReminder(UserId $userId): void
    {
        $assignments = $this->assignmentRepository->findUpcomingByUserId($userId, 3);

        foreach ($assignments as $assignment) {
            $reminder = Reminder::create(
                userId: $userId,
                message: "الواجب '{$assignment->title()}' موعد قريباً",
                triggerAt: $assignment->dueDate()->modify('-2 days')->format('Y-m-d H:i:s'),
                type: ReminderType::IN_APP,
                relatedEntityType: 'assignment',
                relatedEntityId: $assignment->id()->value(),
            );

            $this->reminderRepository->save($reminder);
        }
    }

    public function generateExamReminder(UserId $userId): void
    {
        $exams = $this->examRepository->findUpcomingByUserId($userId, 7);

        foreach ($exams as $exam) {
            $reminder = Reminder::create(
                userId: $userId,
                message: "اختبار '{$exam->title()}' في {$exam->examDate()->format('Y-m-d')}",
                triggerAt: $exam->examDate()->modify('-3 days')->format('Y-m-d H:i:s'),
                type: ReminderType::IN_APP,
                relatedEntityType: 'exam',
                relatedEntityId: $exam->id()->value(),
            );

            $this->reminderRepository->save($reminder);
        }
    }

    public function generateLowProductivityAlert(UserId $userId, int $score): void
    {
        if ($score < 40) {
            $reminder = Reminder::create(
                userId: $userId,
                message: "مؤشر الإنتاجية منخفض ({$score}%) - يرجى مراجعة المهام والأهداف",
                triggerAt: (new \DateTime())->format('Y-m-d H:i:s'),
                type: ReminderType::IN_APP,
                relatedEntityType: 'system',
                relatedEntityId: null,
            );

            $this->reminderRepository->save($reminder);
        }
    }
}
