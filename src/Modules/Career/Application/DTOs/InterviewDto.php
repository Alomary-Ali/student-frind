<?php

declare(strict_types=1);

namespace Modules\Career\Application\DTOs;

final readonly class InterviewDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $type,
        public string $status,
        public string $scheduledAt,
        public array $questions = [],
        public ?int $score = null,
        public ?string $feedback = null,
    ) {}
}
