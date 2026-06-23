<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\DTOs;

final readonly class StudentServicesDashboardDto
{
    public function __construct(
        public int $activeRequests,
        public int $pendingDocuments,
        public int $unreadNotifications,
        public array $recentRequests,
        public array $recentMessages,
        public array $services,
    ) {}
}
