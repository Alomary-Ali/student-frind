<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class FaqDto
{
    public function __construct(
        public string $id,
        public string $categoryId,
        public string $question,
        public string $answer,
        public int $sortOrder,
        public bool $isActive,
    ) {}
}
