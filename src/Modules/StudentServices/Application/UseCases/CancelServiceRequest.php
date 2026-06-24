<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;

final readonly class CancelServiceRequest
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $requestId, string $reason): ?array
    {
        $id = ServiceRequestId::fromString($requestId);
        $request = $this->requests->findById($id);

        if ($request === null) {
            return null;
        }

        $request->cancel($reason);

        $this->requests->save($request);
        $this->events->dispatch($request->releaseEvents());

        return [
            'id' => $request->id()->value(),
            'status' => $request->status()->value,
            'reason' => $reason,
            'updated_at' => $request->updatedAt()->format('c'),
        ];
    }
}
