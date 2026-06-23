<?php
declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;

final readonly class GetWorkflowStatus
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
    ) {}

    public function execute(string $requestId): ?array
    {
        $id = ServiceRequestId::fromString($requestId);
        $request = $this->requests->findById($id);

        if ($request === null) {
            return null;
        }

        return [
            'request_id' => $request->id()->value(),
            'ref_number' => $request->refNumber(),
            'status' => $request->status()->value,
            'workflow_id' => $request->workflowId(),
            'current_step_id' => $request->currentStepId(),
            'updated_at' => $request->updatedAt()->format('c'),
        ];
    }
}
