<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;

final readonly class ListServiceRequests
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
    ) {}

    public function execute(string $studentId, ?string $status = null): array
    {
        if ($status !== null) {
            $all = $this->requests->findByStatus($status);

            return array_filter($all, fn ($r) => $r->studentId() === $studentId);
        }

        return $this->requests->findByStudentId($studentId);
    }
}
