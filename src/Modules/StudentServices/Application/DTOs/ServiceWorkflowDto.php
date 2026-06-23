<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class ServiceWorkflowDto
{
    public function __construct(
        public string $id,
        public string $serviceCategoryId,
        public string $name,
        public string $status,
    ) {}
}
