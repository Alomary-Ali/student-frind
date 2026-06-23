<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\Assignment;
use Modules\Productivity\Domain\ValueObjects\AssignmentId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface AssignmentRepositoryInterface
{
    public function save(Assignment $assignment): void;
    public function findById(AssignmentId $id): ?Assignment;
    public function findByUserId(UserId $userId): array;
    public function findUpcomingByUserId(UserId $userId, int $days = 7): array;
    public function findOverdueByUserId(UserId $userId): array;
    public function delete(AssignmentId $id): void;
}
