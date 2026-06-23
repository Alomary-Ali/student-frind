<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Contracts\Gateways;

interface SkillsGatewayInterface
{
    public function getSkillProfile(string $studentId): ?array;

    public function getSkills(string $studentId): array;

    public function getCertifications(string $studentId): array;

    public function getAchievements(string $studentId): array;

    public function getLearningPaths(string $studentId): array;
}
