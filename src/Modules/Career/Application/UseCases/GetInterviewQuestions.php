<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;
use Modules\Career\Domain\ValueObjects\InterviewId;
use RuntimeException;

final readonly class GetInterviewQuestions
{
    public function __construct(
        private InterviewRepositoryInterface $interviews,
    ) {}

    public function execute(string $interviewId): array
    {
        $interview = $this->interviews->findById(InterviewId::fromString($interviewId));

        if ($interview === null) {
            throw new RuntimeException("Interview not found with ID: {$interviewId}");
        }

        return $interview->questions();
    }
}
