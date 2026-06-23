<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\CareerPathDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\Contracts\Gateways\SkillsGatewayInterface;

final readonly class RecommendCareerPath
{
    public function __construct(
        private CareerPathRepositoryInterface $repository,
        private SkillsGatewayInterface $skills,
        private CareerMapper $mapper,
    ) {}

    /**
     * @return list<array{path: CareerPathDto, match_score: float, matched_skills: list<string>, missing_skills: list<string>}>
     */
    public function execute(string $studentId): array
    {
        $studentSkills = $this->skills->getSkills($studentId);
        $studentSkillNames = array_map(fn (array $skill) => $skill['name'] ?? '', $studentSkills);

        $paths = $this->repository->findAll();

        $scored = [];

        foreach ($paths as $path) {
            $required = $path->getAllRequiredSkills();
            $matched = array_intersect($studentSkillNames, $required);
            $missing = array_diff($required, $studentSkillNames);

            $total = count($required);
            $matchScore = $total > 0 ? count($matched) / $total : 0.0;

            $scored[] = [
                'path' => $this->mapper->toCareerPathDto($path),
                'match_score' => round($matchScore, 4),
                'matched_skills' => array_values($matched),
                'missing_skills' => array_values($missing),
            ];
        }

        usort($scored, fn (array $a, array $b) => $b['match_score'] <=> $a['match_score']);

        return array_slice($scored, 0, 3);
    }
}
