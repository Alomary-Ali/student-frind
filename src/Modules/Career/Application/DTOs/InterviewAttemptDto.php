<?php

declare(strict_types=1);

namespace Modules\Career\Application\DTOs;

final readonly class InterviewAttemptDto
{
    public function __construct(
        public string $id,
        public string $interviewId,
        public string $studentId,
        public array $answers,
        public ?int $score = null,
        public ?string $feedback = null,
        public string $submittedAt = '',
    ) {}
}
