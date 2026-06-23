<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\Project;
use Modules\Productivity\Domain\ValueObjects\ProjectId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface ProjectRepositoryInterface
{
    public function save(Project $project): void;

    public function findById(ProjectId $id): ?Project;

    public function findByUserId(UserId $userId): array;

    public function findActiveByUserId(UserId $userId): array;

    public function delete(ProjectId $id): void;
}
