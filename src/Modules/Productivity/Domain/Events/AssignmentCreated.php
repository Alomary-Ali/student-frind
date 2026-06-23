<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

use Modules\Productivity\Domain\ValueObjects\AssignmentId;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class AssignmentCreated
{
    public function __construct(
        public AssignmentId $assignmentId,
        public UserId $userId,
        public string $courseId,
        public string $title,
        public \DateTimeImmutable $dueDate,
    ) {}
}
