<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\InterviewDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;

final readonly class GetInterviewHistory
{
    public function __construct(
        private InterviewRepositoryInterface $interviews,
        private CareerMapper $mapper,
    ) {}

    /**
     * @return list<InterviewDto>
     */
    public function execute(string $studentId): array
    {
        $interviews = $this->interviews->findByStudentId($studentId);

        return array_map(
            fn ($interview) => $this->mapper->toInterviewDto($interview),
            $interviews,
        );
    }
}
