<?php

declare(strict_types=1);

namespace Modules\Career\Application\DTOs;

final readonly class PublicPortfolioDto
{
    public function __construct(
        public string $id,
        public string $studentId,
        public string $slug,
        public string $title,
        public ?string $bio = null,
        public string $theme = 'modern',
        public bool $isActive = false,
        public int $viewsCount = 0,
    ) {}
}
