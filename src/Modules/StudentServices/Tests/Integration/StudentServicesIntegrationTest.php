<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Modules\StudentServices\Application\UseCases\ApproveServiceRequest;
use Modules\StudentServices\Application\UseCases\CompleteServiceRequest;
use Modules\StudentServices\Application\UseCases\CreateServiceRequest;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceCategory;
use Modules\StudentServices\Infrastructure\Repositories\EloquentServiceRequestRepository;
use Tests\TestCase;

final class StudentServicesIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private ServiceRequestRepositoryInterface $requestRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestRepository = new EloquentServiceRequestRepository;
    }

    public function test_service_request_lifecycle_flow(): void
    {
        // Step 1: Create user and category
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Step 2: Create service request
        $createRequest = new CreateServiceRequest(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $requestDto = $createRequest->execute(
            studentId: $user->id,
            categoryId: $category->id,
            priority: 'medium',
            notes: 'أحتاج الشهادة',
        );

        $this->assertNotNull($requestDto);
        $this->assertEquals('new', $requestDto->status);

        // Step 3: Submit for review
        $request = $this->requestRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ServiceRequestId::fromString($requestDto->id),
        );
        $request->submitForReview('reviewer-1', 'قيد المراجعة');
        $this->requestRepository->save($request);

        // Step 4: Approve request
        $approveRequest = new ApproveServiceRequest(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $approvedDto = $approveRequest->execute(
            requestId: $requestDto->id,
            reviewerId: 'reviewer-1',
            notes: 'معتمد',
        );

        $this->assertEquals('approved', $approvedDto->status);

        // Step 5: Complete request
        $completeRequest = new CompleteServiceRequest(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $completedDto = $completeRequest->execute($requestDto->id);

        $this->assertEquals('completed', $completedDto->status);
    }

    public function test_request_persistence_and_retrieval(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $createRequest = new CreateServiceRequest(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $requestDto = $createRequest->execute($user->id, $category->id, 'high', 'ملاحظات');

        // Retrieve from database
        $retrieved = $this->requestRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ServiceRequestId::fromString($requestDto->id),
        );

        $this->assertNotNull($retrieved);
        $this->assertEquals($requestDto->id, $retrieved->id()->value());
        $this->assertEquals($user->id, $retrieved->studentId());
        $this->assertEquals('high', $retrieved->priority()->value);
    }

    public function test_request_status_transitions(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $createRequest = new CreateServiceRequest(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $requestDto = $createRequest->execute($user->id, $category->id, 'medium');

        $request = $this->requestRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ServiceRequestId::fromString($requestDto->id),
        );

        // Test valid transitions
        $request->submitForReview('reviewer-1');
        $this->assertEquals('under_review', $request->status()->value);

        $request->approve('reviewer-1');
        $this->assertEquals('approved', $request->status()->value);

        $request->complete();
        $this->assertEquals('completed', $request->status()->value);
    }

    public function test_request_attachments_management(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $createRequest = new CreateServiceRequest(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $requestDto = $createRequest->execute($user->id, $category->id, 'medium');

        $request = $this->requestRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ServiceRequestId::fromString($requestDto->id),
        );

        $request->addAttachment('/path/to/file1.pdf');
        $request->addAttachment('/path/to/file2.pdf');
        $this->requestRepository->save($request);

        $retrieved = $this->requestRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ServiceRequestId::fromString($requestDto->id),
        );

        $this->assertCount(2, $retrieved->attachments());
    }

    public function test_student_requests_filtering(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $category = EloquentServiceCategory::create([
            'id' => 'cat-001',
            'name' => 'إثبات قيد',
            'type' => 'academic',
            'description' => 'وصف',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $createRequest = new CreateServiceRequest(
            $this->requestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $createRequest->execute($user->id, $category->id, 'high');
        $createRequest->execute($user->id, $category->id, 'medium');
        $createRequest->execute($user->id, $category->id, 'low');

        $requests = $this->requestRepository->findByStudentId($user->id);

        $this->assertCount(3, $requests);
    }
}
