<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;

final readonly class ExecuteWorkflowStep
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $requestId, string $stepId, string $action): ?array
    {
        $id = ServiceRequestId::fromString($requestId);
        $request = $this->requests->findById($id);

        if ($request === null) {
            return null;
        }

        match ($action) {
            'approve' => $request->approve($stepId),
            'reject' => $request->reject($stepId, 'Rejected at workflow step: ' . $stepId),
            'skip' => null,
            default => null,
        };

        $this->requests->save($request);
        $this->events->dispatch($request->releaseEvents());

        return [
            'request_id' => $request->id()->value(),
            'status' => $request->status()->value,
            'current_step_id' => $request->currentStepId(),
            'step_id' => $stepId,
            'action' => $action,
            'updated_at' => $request->updatedAt()->format('c'),
        ];
    }
}
