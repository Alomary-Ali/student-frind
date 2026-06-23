<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\Contracts\Gateways\OpportunitiesGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\SkillsGatewayInterface;

final readonly class GetUnifiedRecommendations
{
    public function __construct(
        private CareerPathRepositoryInterface $pathRepo,
        private SkillsGatewayInterface $skillsGateway,
        private OpportunitiesGatewayInterface $opportunitiesGateway,
        private RecommendCareerPath $pathRecommender,
    ) {}

    public function execute(string $studentId): array
    {
        $pathRecommendations = $this->pathRecommender->execute($studentId);
        $opportunityRecommendations = $this->opportunitiesGateway->getRecommendedOpportunities($studentId);
        $learningPaths = $this->skillsGateway->getLearningPaths($studentId);
        $skillProfile = $this->skillsGateway->getSkillProfile($studentId);

        return [
            'career_paths' => $pathRecommendations,
            'opportunities' => $opportunityRecommendations,
            'learning_paths' => $learningPaths,
            'skill_summary' => $skillProfile !== null ? [
                'total_skills' => count($skillProfile['skills'] ?? []),
                'total_certifications' => count($skillProfile['certifications'] ?? []),
            ] : null,
        ];
    }
}
