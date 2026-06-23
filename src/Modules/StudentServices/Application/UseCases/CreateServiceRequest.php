<?php
declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\Entities\ServiceRequest;
use Modules\StudentServices\Domain\Enums\RequestPriority;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;

final readonly class CreateServiceRequest
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $studentId, string $categoryId, string $priority, ?string $notes = null): array
    {
        $request = ServiceRequest::create(
            id: ServiceRequestId::generate(),
            studentId: $studentId,
            categoryId: $categoryId,
            refNumber: $this->requests->nextRefNumber(),
            priority: RequestPriority::from($priority),
            notes: $notes,
        );

        $this->requests->save($request);
        $this->events->dispatch($request->releaseEvents());

        return [
            'id' => $request->id()->value(),
            'student_id' => $request->studentId(),
            'category_id' => $request->categoryId(),
            'ref_number' => $request->refNumber(),
            'status' => $request->status()->value,
            'priority' => $request->priority()->value,
            'notes' => $request->notes(),
            'created_at' => $request->createdAt()->format('c'),
        ];
    }
}
