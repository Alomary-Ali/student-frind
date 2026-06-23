<?php

declare(strict_types=1);

namespace Modules\Notifications\Tests\Feature;

use Modules\Notifications\Application\DTOs\NotificationDto;
use Modules\Notifications\Application\Mappers\NotificationMapper;
use Modules\Notifications\Application\UseCases\CreateNotification;
use Modules\Notifications\Application\UseCases\GetStudentNotifications;
use Modules\Notifications\Application\UseCases\MarkNotificationAsRead;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class NotificationUseCasesTest extends TestCase
{
    private NotificationMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new NotificationMapper;
    }

    public function test_it_creates_notification(): void
    {
        $studentId = 'student-123';

        $repo = new class implements NotificationRepositoryInterface
        {
            public ?Notification $saved = null;

            public function findById(NotificationId $id): ?Notification
            {
                return null;
            }

            public function findByStudentId(string $studentId, int $limit = 20): array
            {
                return [];
            }

            public function findUnreadByStudentId(string $studentId): array
            {
                return [];
            }

            public function save(Notification $notification): void
            {
                $this->saved = $notification;
            }

            public function delete(NotificationId $id): void {}
        };

        $events = new class implements EventDispatcherInterface
        {
            public array $dispatched = [];

            public function dispatch(array $events): void
            {
                $this->dispatched = array_merge($this->dispatched, $events);
            }
        };

        $useCase = new CreateNotification($repo, $events, $this->mapper);
        $dto = $useCase->execute($studentId, 'info', 'مرحباً', 'مرحباً بك', 'in_app');

        $this->assertInstanceOf(NotificationDto::class, $dto);
        $this->assertSame($studentId, $dto->studentId);
        $this->assertSame('info', $dto->type);
        $this->assertSame('مرحباً', $dto->title);
        $this->assertFalse($dto->isRead);
        $this->assertNotNull($repo->saved);
        $this->assertCount(1, $events->dispatched);
    }

    public function test_it_gets_student_notifications(): void
    {
        $studentId = 'student-456';
        $notification = Notification::create(
            NotificationId::generate(),
            $studentId,
            \Modules\Notifications\Domain\Enums\NotificationType::SUCCESS,
            'نجاح',
            'تم بنجاح',
            \Modules\Notifications\Domain\Enums\NotificationChannel::IN_APP,
        );
        $notification->releaseEvents();

        $repo = new class($notification) implements NotificationRepositoryInterface
        {
            private Notification $notification;

            public function __construct(Notification $notification)
            {
                $this->notification = $notification;
            }

            public function findById(NotificationId $id): ?Notification
            {
                return null;
            }

            public function findByStudentId(string $studentId, int $limit = 20): array
            {
                return [$this->notification];
            }

            public function findUnreadByStudentId(string $studentId): array
            {
                return [];
            }

            public function save(Notification $notification): void {}

            public function delete(NotificationId $id): void {}
        };

        $useCase = new GetStudentNotifications($repo, $this->mapper);
        $results = $useCase->execute($studentId);

        $this->assertCount(1, $results);
        $this->assertInstanceOf(NotificationDto::class, $results[0]);
        $this->assertSame($studentId, $results[0]->studentId);
        $this->assertSame('success', $results[0]->type);
        $this->assertSame('نجاح', $results[0]->title);
    }

    public function test_it_marks_notification_as_read(): void
    {
        $studentId = 'student-789';
        $notification = Notification::create(
            NotificationId::generate(),
            $studentId,
            \Modules\Notifications\Domain\Enums\NotificationType::WARNING,
            'تحذير',
            'انتبه',
            \Modules\Notifications\Domain\Enums\NotificationChannel::EMAIL,
        );
        $notification->releaseEvents();

        $repo = new class($notification) implements NotificationRepositoryInterface
        {
            private Notification $notification;
            public ?Notification $saved = null;

            public function __construct(Notification $notification)
            {
                $this->notification = $notification;
            }

            public function findById(NotificationId $id): ?Notification
            {
                return $this->notification->id()->equals($id) ? $this->notification : null;
            }

            public function findByStudentId(string $studentId, int $limit = 20): array
            {
                return [];
            }

            public function findUnreadByStudentId(string $studentId): array
            {
                return [];
            }

            public function save(Notification $notification): void
            {
                $this->notification = $notification;
                $this->saved = $notification;
            }

            public function delete(NotificationId $id): void {}
        };

        $useCase = new MarkNotificationAsRead($repo);
        $useCase->execute($notification->id()->value(), $studentId);

        $this->assertNotNull($repo->saved);
        $this->assertTrue($repo->saved->isRead());
    }
}
