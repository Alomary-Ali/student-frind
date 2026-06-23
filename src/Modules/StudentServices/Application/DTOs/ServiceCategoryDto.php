<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class ServiceCategoryDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $type,
        public string $description,
        public bool $isActive,
        public int $sortOrder,
    ) {}
}
