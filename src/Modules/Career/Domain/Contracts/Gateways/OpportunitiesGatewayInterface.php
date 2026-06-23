<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Contracts\Gateways;

interface OpportunitiesGatewayInterface
{
    public function getSavedOpportunities(string $studentId): array;

    public function getApplications(string $studentId): array;

    public function getRecommendations(string $studentId): array;

    public function getRecommendedOpportunities(string $studentId, int $limit = 10): array;
}
