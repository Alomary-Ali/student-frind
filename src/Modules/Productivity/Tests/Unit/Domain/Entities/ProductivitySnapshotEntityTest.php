<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Productivity\Domain\Entities\ProductivitySnapshot;
use Modules\Productivity\Domain\Events\ProductivitySnapshotGenerated;
use Modules\Productivity\Domain\ValueObjects\ProductivitySnapshotId;
use PHPUnit\Framework\TestCase;

final class ProductivitySnapshotEntityTest extends TestCase
{
    public function test_snapshot_can_be_created(): void
    {
        $id = ProductivitySnapshotId::generate();
        $snapshotDate = new DateTimeImmutable('2026-06-23');

        $snapshot = ProductivitySnapshot::create(
            id: $id,
            userId: 'user-1',
            totalGoals: 5,
            completedGoals: 2,
            totalTasks: 20,
            completedTasks: 10,
            overdueTasks: 3,
            snapshotDate: $snapshotDate,
        );

        $this->assertSame($id, $snapshot->id());
        $this->assertSame('user-1', $snapshot->userId());
        $this->assertSame(5, $snapshot->totalGoals());
        $this->assertSame(2, $snapshot->completedGoals());
        $this->assertSame(20, $snapshot->totalTasks());
        $this->assertSame(10, $snapshot->completedTasks());
        $this->assertSame(3, $snapshot->overdueTasks());
        $this->assertSame(50.0, $snapshot->completionRate());
        $this->assertSame($snapshotDate, $snapshot->snapshotDate());
        $this->assertInstanceOf(DateTimeImmutable::class, $snapshot->createdAt());
    }

    public function test_snapshot_completion_rate_zero_when_no_tasks(): void
    {
        $snapshot = ProductivitySnapshot::create(
            id: ProductivitySnapshotId::generate(),
            userId: 'user-1',
            totalGoals: 0,
            completedGoals: 0,
            totalTasks: 0,
            completedTasks: 0,
            overdueTasks: 0,
            snapshotDate: new DateTimeImmutable('2026-06-23'),
        );

        $this->assertSame(0.0, $snapshot->completionRate());
    }

    public function test_snapshot_emits_generated_event(): void
    {
        $snapshot = ProductivitySnapshot::create(
            id: ProductivitySnapshotId::generate(),
            userId: 'user-1',
            totalGoals: 3,
            completedGoals: 1,
            totalTasks: 10,
            completedTasks: 5,
            overdueTasks: 2,
            snapshotDate: new DateTimeImmutable('2026-06-23'),
        );

        $events = $snapshot->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ProductivitySnapshotGenerated::class, $events[0]);
    }

    public function test_snapshot_can_be_reconstituted(): void
    {
        $id = ProductivitySnapshotId::generate();
        $snapshotDate = new DateTimeImmutable('2026-06-23');
        $createdAt = new DateTimeImmutable();

        $snapshot = ProductivitySnapshot::reconstitute(
            id: $id,
            userId: 'user-1',
            totalGoals: 5,
            completedGoals: 3,
            totalTasks: 15,
            completedTasks: 8,
            overdueTasks: 1,
            completionRate: 53.33,
            snapshotDate: $snapshotDate,
            createdAt: $createdAt,
        );

        $this->assertSame($id, $snapshot->id());
        $this->assertSame(5, $snapshot->totalGoals());
        $this->assertSame(3, $snapshot->completedGoals());
        $this->assertSame(15, $snapshot->totalTasks());
        $this->assertSame(8, $snapshot->completedTasks());
        $this->assertSame(1, $snapshot->overdueTasks());
        $this->assertSame(53.33, $snapshot->completionRate());
        $this->assertSame($snapshotDate, $snapshot->snapshotDate());
        $this->assertSame($createdAt, $snapshot->createdAt());
    }

    public function test_snapshot_full_completion(): void
    {
        $snapshot = ProductivitySnapshot::create(
            id: ProductivitySnapshotId::generate(),
            userId: 'user-1',
            totalGoals: 5,
            completedGoals: 5,
            totalTasks: 10,
            completedTasks: 10,
            overdueTasks: 0,
            snapshotDate: new DateTimeImmutable('2026-06-23'),
        );

        $this->assertSame(100.0, $snapshot->completionRate());
    }
}
