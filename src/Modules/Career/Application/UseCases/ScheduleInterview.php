<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use DateTimeImmutable;
use Modules\Career\Application\DTOs\InterviewDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;
use Modules\Career\Domain\Entities\Interview;
use Modules\Career\Domain\Enums\InterviewType;
use Modules\Career\Domain\ValueObjects\InterviewId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class ScheduleInterview
{
    public function __construct(
        private InterviewRepositoryInterface $interviews,
        private EventDispatcherInterface $events,
        private CareerMapper $mapper,
    ) {}

    public function execute(string $studentId, string $type, string $scheduledAt): InterviewDto
    {
        $interview = Interview::create(
            id: InterviewId::generate(),
            studentId: $studentId,
            type: InterviewType::from($type),
            scheduledAt: new DateTimeImmutable($scheduledAt),
        );

        $this->interviews->save($interview);
        $this->events->dispatch($interview->releaseEvents());

        return $this->mapper->toInterviewDto($interview);
    }
}
