<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Contracts;

use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\ValueObjects\NotificationId;

interface NotificationRepositoryInterface
{
    public function findById(NotificationId $id): ?Notification;

    /** @return list<Notification> */
    public function findByStudentId(string $studentId, int $limit = 20): array;

    /** @return list<Notification> */
    public function findUnreadByStudentId(string $studentId): array;

    public function save(Notification $notification): void;

    public function delete(NotificationId $id): void;
}
