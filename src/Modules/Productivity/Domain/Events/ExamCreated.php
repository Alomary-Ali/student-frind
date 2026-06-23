<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Events;

use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class ExamCreated
{
    public function __construct(
        public ExamId $examId,
        public UserId $userId,
        public string $courseId,
        public string $title,
        public \DateTimeImmutable $examDate,
    ) {}
}
