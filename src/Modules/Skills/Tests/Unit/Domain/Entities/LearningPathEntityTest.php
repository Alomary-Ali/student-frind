<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use InvalidArgumentException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use PHPUnit\Framework\TestCase;

final class LearningPathEntityTest extends TestCase
{
    public function test_create_returns_learning_path(): void
    {
        $id = LearningPathId::generate();
        $studentId = StudentId::generate();

        $path = LearningPath::create($id, $studentId, 'مسار تعلم Laravel', 'backend_developer');

        $this->assertSame($id, $path->id());
        $this->assertSame($studentId, $path->studentId());
        $this->assertSame('مسار تعلم Laravel', $path->title());
        $this->assertSame('backend_developer', $path->targetRole());
        $this->assertEmpty($path->steps());
        $this->assertSame(0, $path->progress());
        $this->assertNull($path->estimatedCompletionDate());
    }

    public function test_create_with_steps_and_estimated_completion(): void
    {
        $id = LearningPathId::generate();
        $studentId = StudentId::generate();
        $steps = [
            ['id' => 'step-1', 'title' => 'Step 1', 'completed' => false],
        ];
        $estimatedDate = new DateTimeImmutable('2026-12-31');

        $path = LearningPath::create($id, $studentId, 'Test Path', 'frontend_developer', $steps, $estimatedDate);

        $this->assertCount(1, $path->steps());
        $this->assertSame($estimatedDate, $path->estimatedCompletionDate());
    }

    public function test_reconstitute_restores_learning_path(): void
    {
        $id = LearningPathId::generate();
        $studentId = StudentId::generate();
        $steps = [['id' => 'step-1', 'title' => 'Step 1', 'completed' => true]];
        $date = new DateTimeImmutable('2026-12-31');

        $path = LearningPath::reconstitute($id, $studentId, 'Path', 'role', $steps, 50, $date);

        $this->assertSame(50, $path->progress());
        $this->assertCount(1, $path->steps());
    }

    public function test_update_progress_changes_progress(): void
    {
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role');

        $path->updateProgress(75);
        $this->assertSame(75, $path->progress());
    }

    public function test_update_progress_throws_for_out_of_range(): void
    {
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role');

        $this->expectException(InvalidArgumentException::class);
        $path->updateProgress(-1);
    }

    public function test_update_progress_throws_for_above_100(): void
    {
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role');

        $this->expectException(InvalidArgumentException::class);
        $path->updateProgress(101);
    }

    public function test_update_steps_recalculates_progress(): void
    {
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role');
        $steps = [
            ['id' => 'step-1', 'title' => 'S1', 'completed' => true],
            ['id' => 'step-2', 'title' => 'S2', 'completed' => false],
        ];

        $path->updateSteps($steps);
        $this->assertSame(50, $path->progress());
    }

    public function test_complete_step_marks_step_completed_and_recalculates(): void
    {
        $steps = [
            ['id' => 'step-1', 'title' => 'S1', 'completed' => false],
            ['id' => 'step-2', 'title' => 'S2', 'completed' => false],
        ];
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role', $steps);

        $path->completeStep('step-1');

        $this->assertTrue($path->steps()[0]['completed']);
        $this->assertArrayHasKey('completed_at', $path->steps()[0]);
        $this->assertFalse($path->steps()[1]['completed']);
        $this->assertSame(50, $path->progress());
    }

    public function test_complete_step_unknown_does_not_affect_progress(): void
    {
        $steps = [
            ['id' => 'step-1', 'title' => 'S1', 'completed' => false],
        ];
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role', $steps);

        $path->completeStep('unknown-step');

        $this->assertFalse($path->steps()[0]['completed']);
        $this->assertSame(0, $path->progress());
    }

    public function test_progress_is_zero_when_no_steps(): void
    {
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role');

        $path->updateSteps([]);
        $this->assertSame(0, $path->progress());
    }

    public function test_all_steps_completed_gives_100_percent(): void
    {
        $steps = [
            ['id' => 'step-1', 'title' => 'S1', 'completed' => false],
            ['id' => 'step-2', 'title' => 'S2', 'completed' => false],
        ];
        $path = LearningPath::create(LearningPathId::generate(), StudentId::generate(), 'T', 'role', $steps);

        $path->completeStep('step-1');
        $path->completeStep('step-2');

        $this->assertSame(100, $path->progress());
    }
}
