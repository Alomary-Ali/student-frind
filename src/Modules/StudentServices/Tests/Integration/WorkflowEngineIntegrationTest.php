<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\StudentServices\Application\UseCases\DefineWorkflow;
use Modules\StudentServices\Application\UseCases\ExecuteWorkflowStep;
use Modules\StudentServices\Application\UseCases\GetWorkflowStatus;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceCategory;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceWorkflow;
use Modules\StudentServices\Infrastructure\Repositories\EloquentServiceRequestRepository;
use Tests\TestCase;

final class WorkflowEngineIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private ServiceRequestRepositoryInterface $requestRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestRepository = new EloquentServiceRequestRepository;
    }

    public function test_workflow_definition_and_execution_flow(): void
    {
        // Step 1: Create service category
        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Step 2: Define workflow
        $defineWorkflow = new DefineWorkflow(
            $this->createMock(\Modules\StudentServices\Domain\Contracts\ServiceWorkflowRepositoryInterface::class),
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $workflowDto = $defineWorkflow->execute(
            serviceCategoryId: $category->id,
            name: 'سير عمل إثبات قيد',
            steps: [
                [
                    'name' => 'تقديم الطلب',
                    'type' => 'form',
                    'order' => 1,
                    'assignee_role' => 'student',
                    'config' => json_encode(['required_fields' => ['name', 'id']]),
                ],
                [
                    'name' => 'مراجعة',
                    'type' => 'approval',
                    'order' => 2,
                    'assignee_role' => 'admin',
                    'config' => json_encode(['auto_approve' => false]),
                ],
                [
                    'name' => 'إصدار المستند',
                    'type' => 'document',
                    'order' => 3,
                    'assignee_role' => 'system',
                    'config' => json_encode(['template' => 'certificate']),
                ],
            ],
        );

        $this->assertNotNull($workflowDto);
        $this->assertEquals('active', $workflowDto->status);
        $this->assertCount(3, $workflowDto->steps);

        // Step 3: Get workflow status
        $getWorkflowStatus = new GetWorkflowStatus(
            $this->createMock(\Modules\StudentServices\Domain\Contracts\ServiceWorkflowRepositoryInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $statusDto = $getWorkflowStatus->execute($workflowDto->id);

        $this->assertNotNull($statusDto);
        $this->assertEquals($workflowDto->id, $statusDto->id);
    }

    public function test_workflow_step_execution(): void
    {
        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $workflow = EloquentServiceWorkflow::create([
            'id' => 'wf-001',
            'service_category_id' => $category->id,
            'name' => 'سير عمل',
            'status' => 'active',
        ]);

        $executeStep = new ExecuteWorkflowStep(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $stepDto = $executeStep->execute(
            requestId: 'req-001',
            stepId: 'step-001',
            data: ['status' => 'completed', 'notes' => 'تم بنجاح'],
        );

        $this->assertNotNull($stepDto);
    }

    public function test_workflow_persistence_and_retrieval(): void
    {
        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $workflow = EloquentServiceWorkflow::create([
            'id' => 'wf-001',
            'service_category_id' => $category->id,
            'name' => 'سير عمل',
            'status' => 'active',
        ]);

        // Retrieve from database
        $repository = new \Modules\StudentServices\Infrastructure\Repositories\EloquentServiceWorkflowRepository;
        $retrieved = $repository->findById($workflow->id);

        $this->assertNotNull($retrieved);
        $this->assertEquals($workflow->id, $retrieved->id());
        $this->assertEquals($category->id, $retrieved->serviceCategoryId());
    }

    public function test_workflow_status_transitions(): void
    {
        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $workflow = EloquentServiceWorkflow::create([
            'id' => 'wf-001',
            'service_category_id' => $category->id,
            'name' => 'سير عمل',
            'status' => 'active',
        ]);

        // Test status transitions
        $workflow->deactivate();
        $this->assertEquals('inactive', $workflow->status()->value);

        $workflow->activate();
        $this->assertEquals('active', $workflow->status()->value);
    }

    public function test_workflow_steps_management(): void
    {
        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $workflow = EloquentServiceWorkflow::create([
            'id' => 'wf-001',
            'service_category_id' => $category->id,
            'name' => 'سير عمل',
            'status' => 'active',
        ]);

        // Add steps
        $workflow->addStep([
            'id' => 'step-001',
            'name' => 'الخطوة 1',
            'type' => 'form',
            'order' => 1,
            'assignee_role' => 'student',
            'config' => '{}',
        ]);

        $workflow->addStep([
            'id' => 'step-002',
            'name' => 'الخطوة 2',
            'type' => 'approval',
            'order' => 2,
            'assignee_role' => 'admin',
            'config' => '{}',
        ]);

        $this->assertCount(2, $workflow->steps());
        $this->assertEquals('الخطوة 1', $workflow->steps()[0]['name']);
    }

    public function test_workflow_category_association(): void
    {
        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $workflow = EloquentServiceWorkflow::create([
            'id' => 'wf-001',
            'service_category_id' => $category->id,
            'name' => 'سير عمل',
            'status' => 'active',
        ]);

        $repository = new \Modules\StudentServices\Infrastructure\Repositories\EloquentServiceWorkflowRepository;
        $categoryWorkflows = $repository->findByCategoryId($category->id);

        $this->assertCount(1, $categoryWorkflows);
        $this->assertEquals($workflow->id, $categoryWorkflows[0]->id());
    }
}
