<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\Reminder;
use Modules\Productivity\Domain\ValueObjects\ReminderId;

interface ReminderRepositoryInterface
{
    public function findById(ReminderId $id): ?Reminder;

    public function findByUserId(string $userId): array;

    public function findDueByUserId(string $userId): array;

    public function save(Reminder $reminder): void;

    public function delete(ReminderId $id): void;
}
