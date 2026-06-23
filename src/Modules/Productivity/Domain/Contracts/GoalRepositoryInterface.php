<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\Goal;
use Modules\Productivity\Domain\ValueObjects\GoalId;

interface GoalRepositoryInterface
{
    public function findById(GoalId $id): ?Goal;

    public function findByUserId(string $userId): array;

    public function findActiveByUserId(string $userId): array;

    public function save(Goal $goal): void;

    public function delete(GoalId $id): void;
}
