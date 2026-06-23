<?php

declare(strict_types=1);

namespace Modules\Career\Application\DTOs;

final readonly class InterviewQuestionDto
{
    public function __construct(
        public string $id,
        public string $interviewId,
        public string $question,
        public ?string $category = null,
        public int $order = 0,
    ) {}
}
