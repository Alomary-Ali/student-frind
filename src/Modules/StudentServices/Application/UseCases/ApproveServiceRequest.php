<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;

final readonly class ApproveServiceRequest
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $requestId, string $reviewerId): ?array
    {
        $id = ServiceRequestId::fromString($requestId);
        $request = $this->requests->findById($id);

        if ($request === null) {
            return null;
        }

        $request->approve($reviewerId);

        $this->requests->save($request);
        $this->events->dispatch($request->releaseEvents());

        return [
            'id' => $request->id()->value(),
            'status' => $request->status()->value,
            'updated_at' => $request->updatedAt()->format('c'),
        ];
    }
}
