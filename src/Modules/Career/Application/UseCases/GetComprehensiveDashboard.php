<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\ComprehensiveDashboardDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\Contracts\Gateways\CareerProfileGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\OpportunitiesGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\SkillsGatewayInterface;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;

final readonly class GetComprehensiveDashboard
{
    public function __construct(
        private CareerProfileGatewayInterface $careerProfileGateway,
        private SkillsGatewayInterface $skillsGateway,
        private OpportunitiesGatewayInterface $opportunitiesGateway,
        private InterviewRepositoryInterface $interviewRepo,
        private CareerPathRepositoryInterface $careerPathRepo,
        private CareerMapper $mapper,
        private CalculateEmploymentReadiness $readinessCalculator,
    ) {}

    public function execute(string $studentId, ?float $gpa = null): ComprehensiveDashboardDto
    {
        $profile = $this->careerProfileGateway->getDashboard($studentId);
        $skillProfile = $this->skillsGateway->getSkillProfile($studentId);
        $opportunities = [
            'saved' => $this->opportunitiesGateway->getSavedOpportunities($studentId),
            'applications' => $this->opportunitiesGateway->getApplications($studentId),
            'recommendations' => $this->opportunitiesGateway->getRecommendedOpportunities($studentId),
        ];

        $interviews = array_map(
            fn ($i) => $this->mapper->toInterviewDto($i),
            $this->interviewRepo->findByStudentId($studentId),
        );

        $careerPaths = array_map(
            fn ($p) => $this->mapper->toCareerPathDto($p),
            $this->careerPathRepo->findAll(),
        );

        $readiness = $this->readinessCalculator->execute($studentId, $gpa);

        return $this->mapper->toComprehensiveDashboardDto(
            profile: $profile,
            skillProfile: $skillProfile,
            opportunities: $opportunities,
            interviews: $interviews,
            careerPaths: $careerPaths,
            readinessScore: $readiness['score'],
            readinessBreakdown: $readiness['breakdown'],
        );
    }
}
