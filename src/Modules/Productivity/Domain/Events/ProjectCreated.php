<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

use Modules\Productivity\Domain\ValueObjects\ProjectId;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class ProjectCreated
{
    public function __construct(
        public ProjectId $projectId,
        public UserId $userId,
        public string $title,
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $dueDate,
    ) {}
}
