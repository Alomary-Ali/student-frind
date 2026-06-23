<?php

declare(strict_types=1);

namespace Tests\Integration\Productivity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Productivity\Application\DTOs\CreateAssignmentDto;
use Modules\Productivity\Application\UseCases\CreateAssignment;
use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignmentRepository;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignment;
use Modules\Shared\Domain\ValueObjects\UserId;
use Tests\TestCase;

final class ProductivityModuleIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_repository_integration(): void
    {
        $user = User::factory()->create();
        $eloquentAssignment = EloquentAssignment::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'course_id' => 'CS101',
            'title' => 'واجب البرمجة',
            'description' => 'حل مسائل البرمجة الأساسية',
            'assigned_at' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'assigned',
        ]);

        $repository = app(AssignmentRepositoryInterface::class);

        $this->assertInstanceOf(EloquentAssignmentRepository::class, $repository);

        $assignment = $repository->findById($eloquentAssignment->id);

        $this->assertNotNull($assignment);
        $this->assertEquals('واجب البرمجة', $assignment->title());
    }

    public function test_create_assignment_use_case_integration(): void
    {
        $user = User::factory()->create();
        $repository = app(AssignmentRepositoryInterface::class);
        $useCase = new CreateAssignment($repository);

        $dto = new CreateAssignmentDto(
            userId: UserId::fromString($user->id)->value(),
            courseId: 'CS101',
            title: 'واجب البرمجة',
            description: 'حل مسائل البرمجة الأساسية',
            dueDate: new \DateTimeImmutable('+7 days'),
        );

        $result = $useCase->execute($dto);

        $this->assertNotNull($result->id());
        $this->assertEquals('واجب البرمجة', $result->title());

        $savedAssignment = $repository->findById($result->id()->value());
        $this->assertNotNull($savedAssignment);
    }
}
