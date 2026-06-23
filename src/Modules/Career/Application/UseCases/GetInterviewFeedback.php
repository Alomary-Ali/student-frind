<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\InterviewDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;
use Modules\Career\Domain\Enums\InterviewStatus;
use Modules\Career\Domain\ValueObjects\InterviewId;
use RuntimeException;

final readonly class GetInterviewFeedback
{
    public function __construct(
        private InterviewRepositoryInterface $interviews,
        private CareerMapper $mapper,
    ) {}

    public function execute(string $interviewId): InterviewDto
    {
        $interview = $this->interviews->findById(InterviewId::fromString($interviewId));

        if ($interview === null) {
            throw new RuntimeException("Interview not found with ID: {$interviewId}");
        }

        if ($interview->status() !== InterviewStatus::COMPLETED) {
            throw new RuntimeException('Interview has not been completed yet.');
        }

        return $this->mapper->toInterviewDto($interview);
    }
}
