<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Contracts\Gateways;

interface CareerProfileGatewayInterface
{
    public function getProfile(string $studentId): ?array;

    public function getPortfolioItems(string $studentId): array;

    public function getExperiences(string $studentId): array;

    public function getResumes(string $studentId): array;

    public function getCareerGoals(string $studentId): array;

    public function getDashboard(string $studentId, ?float $gpa = null): ?array;
}
