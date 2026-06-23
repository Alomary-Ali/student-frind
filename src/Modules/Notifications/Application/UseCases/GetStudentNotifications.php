<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\UseCases;

use Modules\Notifications\Application\DTOs\NotificationDto;
use Modules\Notifications\Application\Mappers\NotificationMapper;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;

final readonly class GetStudentNotifications
{
    public function __construct(
        private NotificationRepositoryInterface $notifications,
        private NotificationMapper $mapper,
    ) {}

    /** @return list<NotificationDto> */
    public function execute(string $studentId, int $limit = 20): array
    {
        $entities = $this->notifications->findByStudentId($studentId, $limit);

        return array_map(fn ($entity) => $this->mapper->toDto($entity), $entities);
    }
}
