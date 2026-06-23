<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\DTOs;

final readonly class ApplicationDto
{
    public function __construct(
        public string $id,
        public string $opportunityId,
        public string $studentId,
        public string $status,
        public ?string $appliedAt,
        public ?string $notes,
    ) {}
}
