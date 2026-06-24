<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Entities\ServiceWorkflow;
use Modules\StudentServices\Domain\Enums\WorkflowStatus;
use PHPUnit\Framework\TestCase;

final class ServiceWorkflowEntityTest extends TestCase
{
    public function test_create_returns_workflow_with_active_status(): void
    {
        $workflow = ServiceWorkflow::create('category-1', 'طلب إثبات قيد');

        $this->assertNotEmpty($workflow->id());
        $this->assertSame('category-1', $workflow->serviceCategoryId());
        $this->assertSame('طلب إثبات قيد', $workflow->name());
        $this->assertSame(WorkflowStatus::ACTIVE, $workflow->status());
        $this->assertEmpty($workflow->steps());
    }

    public function test_create_with_steps(): void
    {
        $steps = [
            ['id' => 'step-1', 'name' => 'تقديم الطلب', 'type' => 'form', 'order' => 1],
            ['id' => 'step-2', 'name' => 'مراجعة', 'type' => 'approval', 'order' => 2],
        ];

        $workflow = ServiceWorkflow::create('category-1', 'طلب', $steps);

        $this->assertCount(2, $workflow->steps());
        $this->assertSame('تقديم الطلب', $workflow->steps()[0]['name']);
    }

    public function test_activate_changes_status(): void
    {
        $workflow = ServiceWorkflow::create('category-1', 'طلب');
        $workflow->deactivate();

        $workflow->activate();

        $this->assertSame(WorkflowStatus::ACTIVE, $workflow->status());
    }

    public function test_deactivate_changes_status(): void
    {
        $workflow = ServiceWorkflow::create('category-1', 'طلب');

        $workflow->deactivate();

        $this->assertSame(WorkflowStatus::INACTIVE, $workflow->status());
    }

    public function test_add_step_appends_to_steps(): void
    {
        $workflow = ServiceWorkflow::create('category-1', 'طلب');

        $workflow->addStep(['id' => 'step-1', 'name' => 'الخطوة 1', 'type' => 'form', 'order' => 1]);
        $workflow->addStep(['id' => 'step-2', 'name' => 'الخطوة 2', 'type' => 'approval', 'order' => 2]);

        $this->assertCount(2, $workflow->steps());
        $this->assertSame('الخطوة 1', $workflow->steps()[0]['name']);
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = 'workflow-123';
        $now = new DateTimeImmutable;
        $steps = [['id' => 'step-1', 'name' => 'الخطوة 1']];

        $workflow = ServiceWorkflow::reconstitute(
            id: $id,
            serviceCategoryId: 'category-1',
            name: 'طلب إثبات قيد',
            status: WorkflowStatus::INACTIVE,
            steps: $steps,
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id, $workflow->id());
        $this->assertSame(WorkflowStatus::INACTIVE, $workflow->status());
        $this->assertSame(['id' => 'step-1', 'name' => 'الخطوة 1'], $workflow->steps()[0]);
    }

    public function test_release_events_clears_events(): void
    {
        $workflow = ServiceWorkflow::create('category-1', 'طلب');

        $this->assertCount(0, $workflow->releaseEvents());
    }
}
