<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\DTOs;

final readonly class ResumeDto
{
    public function __construct(
        public string $id,
        public string $careerProfileId,
        public string $template,
        public string $content,
        public string $generatedAt,
    ) {}
}
