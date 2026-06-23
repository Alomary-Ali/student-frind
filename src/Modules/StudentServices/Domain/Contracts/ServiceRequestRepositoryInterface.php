<?php
declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts;

use Modules\StudentServices\Domain\Entities\ServiceRequest;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;

interface ServiceRequestRepositoryInterface
{
    public function findById(ServiceRequestId $id): ?ServiceRequest;
    public function findByStudentId(string $studentId): array;
    public function findByStatus(string $status): array;
    public function findByRefNumber(string $refNumber): ?ServiceRequest;
    public function save(ServiceRequest $request): void;
    public function nextRefNumber(): string;
}
