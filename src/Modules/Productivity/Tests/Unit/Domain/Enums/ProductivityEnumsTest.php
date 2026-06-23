<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Enums;

use Modules\Productivity\Domain\Enums\GoalStatus;
use Modules\Productivity\Domain\Enums\GoalType;
use Modules\Productivity\Domain\Enums\TaskStatus;
use Modules\Productivity\Domain\Enums\TaskPriority;
use Modules\Productivity\Domain\Enums\AssignmentStatus;
use Modules\Productivity\Domain\Enums\ExamType;
use Modules\Productivity\Domain\Enums\ProjectStatus;
use Modules\Productivity\Domain\Enums\EventType;
use Modules\Productivity\Domain\Enums\NotificationType;
use Modules\Productivity\Domain\Enums\ReminderType;
use Modules\Productivity\Domain\Enums\ReminderStatus;
use Modules\Productivity\Domain\Enums\ReadinessStatus;
use PHPUnit\Framework\TestCase;

final class ProductivityEnumsTest extends TestCase
{
    public function test_goal_status_values(): void
    {
        $this->assertSame('active', GoalStatus::Active->value);
        $this->assertSame('completed', GoalStatus::Completed->value);
        $this->assertSame('archived', GoalStatus::Archived->value);
    }

    public function test_goal_status_methods(): void
    {
        $this->assertTrue(GoalStatus::Completed->isCompleted());
        $this->assertFalse(GoalStatus::Active->isCompleted());
        $this->assertTrue(GoalStatus::Active->isActive());
        $this->assertFalse(GoalStatus::Completed->isActive());
        $this->assertTrue(GoalStatus::Archived->isArchived());
        $this->assertFalse(GoalStatus::Active->isArchived());
    }

    public function test_goal_type_values(): void
    {
        $this->assertSame('daily', GoalType::Daily->value);
        $this->assertSame('semester', GoalType::Semester->value);
        $this->assertSame('long_term', GoalType::LongTerm->value);
    }

    public function test_goal_type_label(): void
    {
        $this->assertSame('يومي', GoalType::Daily->label());
        $this->assertSame('فصلي', GoalType::Semester->label());
        $this->assertSame('طويل المدى', GoalType::LongTerm->label());
    }

    public function test_task_status_values(): void
    {
        $this->assertSame('pending', TaskStatus::Pending->value);
        $this->assertSame('in_progress', TaskStatus::InProgress->value);
        $this->assertSame('completed', TaskStatus::Completed->value);
        $this->assertSame('postponed', TaskStatus::Postponed->value);
        $this->assertSame('cancelled', TaskStatus::Cancelled->value);
    }

    public function test_task_status_methods(): void
    {
        $this->assertTrue(TaskStatus::Completed->isCompleted());
        $this->assertTrue(TaskStatus::Pending->isPending());
        $this->assertTrue(TaskStatus::InProgress->isInProgress());
        $this->assertTrue(TaskStatus::Postponed->isPostponed());
        $this->assertTrue(TaskStatus::Cancelled->isCancelled());
        $this->assertFalse(TaskStatus::Pending->isCompleted());
    }

    public function test_task_priority_values(): void
    {
        $this->assertSame('low', TaskPriority::LOW->value);
        $this->assertSame('medium', TaskPriority::MEDIUM->value);
        $this->assertSame('high', TaskPriority::HIGH->value);
        $this->assertSame('urgent', TaskPriority::URGENT->value);
    }

    public function test_task_priority_weight(): void
    {
        $this->assertSame(1, TaskPriority::LOW->weight());
        $this->assertSame(2, TaskPriority::MEDIUM->weight());
        $this->assertSame(3, TaskPriority::HIGH->weight());
        $this->assertSame(4, TaskPriority::URGENT->weight());
    }

    public function test_task_priority_label(): void
    {
        $this->assertSame('منخفض', TaskPriority::LOW->label());
        $this->assertSame('متوسط', TaskPriority::MEDIUM->label());
        $this->assertSame('عالي', TaskPriority::HIGH->label());
        $this->assertSame('عاجل', TaskPriority::URGENT->label());
    }

    public function test_assignment_status_values(): void
    {
        $this->assertSame('assigned', AssignmentStatus::ASSIGNED->value);
        $this->assertSame('in_progress', AssignmentStatus::IN_PROGRESS->value);
        $this->assertSame('submitted', AssignmentStatus::SUBMITTED->value);
        $this->assertSame('graded', AssignmentStatus::GRADED->value);
        $this->assertSame('late', AssignmentStatus::LATE->value);
    }

    public function test_assignment_status_is_completed(): void
    {
        $this->assertTrue(AssignmentStatus::SUBMITTED->isCompleted());
        $this->assertTrue(AssignmentStatus::GRADED->isCompleted());
        $this->assertFalse(AssignmentStatus::ASSIGNED->isCompleted());
    }

    public function test_assignment_status_is_overdue(): void
    {
        $this->assertTrue(AssignmentStatus::LATE->isOverdue());
        $this->assertFalse(AssignmentStatus::ASSIGNED->isOverdue());
    }

    public function test_exam_type_values(): void
    {
        $this->assertSame('midterm', ExamType::MIDTERM->value);
        $this->assertSame('final', ExamType::FINAL->value);
        $this->assertSame('quiz', ExamType::QUIZ->value);
        $this->assertSame('practical', ExamType::PRACTICAL->value);
        $this->assertSame('oral', ExamType::ORAL->value);
    }

    public function test_exam_type_weight(): void
    {
        $this->assertSame(0.4, ExamType::FINAL->weight());
        $this->assertSame(0.3, ExamType::MIDTERM->weight());
        $this->assertSame(0.2, ExamType::PRACTICAL->weight());
        $this->assertSame(0.05, ExamType::QUIZ->weight());
        $this->assertSame(0.05, ExamType::ORAL->weight());
    }

    public function test_project_status_values(): void
    {
        $this->assertSame('planning', ProjectStatus::PLANNING->value);
        $this->assertSame('in_progress', ProjectStatus::IN_PROGRESS->value);
        $this->assertSame('on_hold', ProjectStatus::ON_HOLD->value);
        $this->assertSame('completed', ProjectStatus::COMPLETED->value);
        $this->assertSame('cancelled', ProjectStatus::CANCELLED->value);
    }

    public function test_project_status_is_active(): void
    {
        $this->assertTrue(ProjectStatus::PLANNING->isActive());
        $this->assertTrue(ProjectStatus::IN_PROGRESS->isActive());
        $this->assertFalse(ProjectStatus::COMPLETED->isActive());
        $this->assertFalse(ProjectStatus::CANCELLED->isActive());
    }

    public function test_event_type_values(): void
    {
        $this->assertSame('task', EventType::TASK->value);
        $this->assertSame('exam', EventType::EXAM->value);
        $this->assertSame('assignment', EventType::ASSIGNMENT->value);
        $this->assertSame('project', EventType::PROJECT->value);
        $this->assertSame('personal', EventType::PERSONAL->value);
        $this->assertSame('academic', EventType::ACADEMIC->value);
    }

    public function test_notification_type_values(): void
    {
        $this->assertSame('task_due', NotificationType::TASK_DUE->value);
        $this->assertSame('task_overdue', NotificationType::TASK_OVERDUE->value);
        $this->assertSame('goal_deadline', NotificationType::GOAL_DEADLINE->value);
        $this->assertSame('exam_reminder', NotificationType::EXAM_REMINDER->value);
        $this->assertSame('assignment_due', NotificationType::ASSIGNMENT_DUE->value);
        $this->assertSame('low_productivity', NotificationType::LOW_PRODUCTIVITY->value);
        $this->assertSame('project_deadline', NotificationType::PROJECT_DEADLINE->value);
        $this->assertSame('system', NotificationType::SYSTEM->value);
    }

    public function test_notification_type_is_urgent(): void
    {
        $this->assertTrue(NotificationType::TASK_OVERDUE->isUrgent());
        $this->assertTrue(NotificationType::EXAM_REMINDER->isUrgent());
        $this->assertTrue(NotificationType::ASSIGNMENT_DUE->isUrgent());
        $this->assertFalse(NotificationType::TASK_DUE->isUrgent());
        $this->assertFalse(NotificationType::SYSTEM->isUrgent());
    }

    public function test_reminder_type_values(): void
    {
        $this->assertSame('email', ReminderType::Email->value);
        $this->assertSame('push', ReminderType::Push->value);
        $this->assertSame('in_app', ReminderType::InApp->value);
    }

    public function test_reminder_type_methods(): void
    {
        $this->assertTrue(ReminderType::Email->isEmail());
        $this->assertTrue(ReminderType::Push->isPush());
        $this->assertTrue(ReminderType::InApp->isInApp());
        $this->assertFalse(ReminderType::Email->isPush());
    }

    public function test_reminder_status_values(): void
    {
        $this->assertSame('pending', ReminderStatus::Pending->value);
        $this->assertSame('triggered', ReminderStatus::Triggered->value);
        $this->assertSame('dismissed', ReminderStatus::Dismissed->value);
    }

    public function test_reminder_status_methods(): void
    {
        $this->assertTrue(ReminderStatus::Pending->isPending());
        $this->assertTrue(ReminderStatus::Triggered->isTriggered());
        $this->assertTrue(ReminderStatus::Dismissed->isDismissed());
    }

    public function test_readiness_status_values(): void
    {
        $this->assertSame('not_ready', ReadinessStatus::NotReady->value);
        $this->assertSame('needs_review', ReadinessStatus::NeedsReview->value);
        $this->assertSame('partially_ready', ReadinessStatus::PartiallyReady->value);
        $this->assertSame('fully_ready', ReadinessStatus::FullyReady->value);
    }

    public function test_readiness_status_label(): void
    {
        $this->assertSame('غير مستعد', ReadinessStatus::NotReady->label());
        $this->assertSame('يحتاج مراجعة', ReadinessStatus::NeedsReview->label());
        $this->assertSame('جاهز جزئياً', ReadinessStatus::PartiallyReady->label());
        $this->assertSame('جاهز بالكامل', ReadinessStatus::FullyReady->label());
    }

    public function test_readiness_status_color(): void
    {
        $this->assertSame('#EF4444', ReadinessStatus::NotReady->color());
        $this->assertSame('#F59E0B', ReadinessStatus::NeedsReview->color());
        $this->assertSame('#243B7C', ReadinessStatus::PartiallyReady->color());
        $this->assertSame('#10B981', ReadinessStatus::FullyReady->color());
    }
}
