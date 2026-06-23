<?php

declare(strict_types=1);

namespace Modules\Opportunities\Application\DTOs;

final readonly class OpportunityDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $provider,
        public string $type,
        public ?string $location,
        public ?string $country,
        public ?string $deadline,
        public ?string $applyUrl,
        public string $status,
        public array $metadata,
        public ?string $sourceUrl,
        public ?string $imageUrl,
        public array $tags,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
