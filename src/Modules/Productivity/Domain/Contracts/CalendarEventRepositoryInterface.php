<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\CalendarEvent;
use Modules\Productivity\Domain\ValueObjects\CalendarEventId;

interface CalendarEventRepositoryInterface
{
    public function findById(CalendarEventId $id): ?CalendarEvent;

    public function findByUserId(string $userId): array;

    public function findByDateRange(string $userId, \DateTimeImmutable $start, \DateTimeImmutable $end): array;

    public function save(CalendarEvent $event): void;

    public function delete(CalendarEventId $id): void;
}
