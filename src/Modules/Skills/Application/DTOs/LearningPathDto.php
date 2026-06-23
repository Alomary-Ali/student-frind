<?php

declare(strict_types=1);

namespace Modules\Skills\Application\DTOs;

final readonly class LearningPathDto
{
    /**
     * @param  array<array<string,mixed>>  $steps
     */
    public function __construct(
        public string $id,
        public string $studentId,
        public string $title,
        public string $targetRole,
        public array $steps,
        public int $progress,
        public ?string $estimatedCompletionDate,
    ) {}
}
