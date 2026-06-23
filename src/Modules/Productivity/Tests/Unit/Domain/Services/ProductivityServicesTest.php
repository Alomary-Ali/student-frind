<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Services;

use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\Services\NotificationService;
use Modules\Productivity\Domain\Services\PriorityEngine;
use Modules\Productivity\Domain\Services\ProductivityScoreEngine;
use PHPUnit\Framework\TestCase;

final class ProductivityServicesTest extends TestCase
{
    public function test_notification_service_constructor(): void
    {
        $reminderRepo = $this->createMock(ReminderRepositoryInterface::class);
        $taskRepo = $this->createMock(TaskRepositoryInterface::class);
        $assignmentRepo = $this->createMock(AssignmentRepositoryInterface::class);
        $examRepo = $this->createMock(ExamRepositoryInterface::class);

        $service = new NotificationService(
            reminderRepository: $reminderRepo,
            taskRepository: $taskRepo,
            assignmentRepository: $assignmentRepo,
            examRepository: $examRepo,
        );

        $this->assertInstanceOf(NotificationService::class, $service);
    }

    public function test_priority_engine_constructor(): void
    {
        $taskRepo = $this->createMock(TaskRepositoryInterface::class);
        $assignmentRepo = $this->createMock(AssignmentRepositoryInterface::class);
        $examRepo = $this->createMock(ExamRepositoryInterface::class);

        $engine = new PriorityEngine(
            taskRepository: $taskRepo,
            assignmentRepository: $assignmentRepo,
            examRepository: $examRepo,
        );

        $this->assertInstanceOf(PriorityEngine::class, $engine);
    }

    public function test_productivity_score_engine_score_levels(): void
    {
        $engine = new ProductivityScoreEngine(
            $this->createMock(TaskRepositoryInterface::class),
            $this->createMock(GoalRepositoryInterface::class),
        );

        $this->assertSame('ممتاز', $engine->getScoreLevel(95));
        $this->assertSame('جيد جداً', $engine->getScoreLevel(80));
        $this->assertSame('جيد', $engine->getScoreLevel(65));
        $this->assertSame('متوسط', $engine->getScoreLevel(50));
        $this->assertSame('يحتاج تحسين', $engine->getScoreLevel(30));
    }
}
