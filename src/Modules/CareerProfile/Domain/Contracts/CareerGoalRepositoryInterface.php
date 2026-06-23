<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Contracts;

use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;

interface CareerGoalRepositoryInterface
{
    public function findById(CareerGoalId $id): ?CareerGoal;

    public function save(CareerGoal $goal): void;

    public function delete(CareerGoalId $id): void;
}
