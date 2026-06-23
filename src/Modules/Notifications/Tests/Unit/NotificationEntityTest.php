<?php

declare(strict_types=1);

namespace Modules\Notifications\Tests\Unit;

use Modules\Notifications\Application\DTOs\NotificationDto;
use Modules\Notifications\Application\Mappers\NotificationMapper;
use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\Enums\NotificationChannel;
use Modules\Notifications\Domain\Enums\NotificationType;
use Modules\Notifications\Domain\Events\NotificationCreated;
use Modules\Notifications\Domain\ValueObjects\NotificationId;
use PHPUnit\Framework\TestCase;

final class NotificationEntityTest extends TestCase
{
    public function test_create_returns_notification_with_defaults(): void
    {
        $id = NotificationId::generate();

        $notification = Notification::create(
            $id,
            'student-1',
            NotificationType::INFO,
            'مرحباً',
            'مرحباً بك في المنصة',
            NotificationChannel::IN_APP,
        );

        $this->assertSame($id, $notification->id());
        $this->assertSame('student-1', $notification->studentId());
        $this->assertSame(NotificationType::INFO, $notification->type());
        $this->assertSame('مرحباً', $notification->title());
        $this->assertSame('مرحباً بك في المنصة', $notification->message());
        $this->assertSame(NotificationChannel::IN_APP, $notification->channel());
        $this->assertNull($notification->link());
        $this->assertFalse($notification->isRead());
    }

    public function test_create_dispatches_notification_created_event(): void
    {
        $id = NotificationId::generate();

        $notification = Notification::create(
            $id,
            'student-1',
            NotificationType::SUCCESS,
            'تم بنجاح',
            'تمت العملية بنجاح',
            NotificationChannel::IN_APP,
        );

        $events = $notification->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(NotificationCreated::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->id);
        $this->assertSame('student-1', $events[0]->studentId);
    }

    public function test_mark_as_read_updates_status(): void
    {
        $notification = Notification::create(
            NotificationId::generate(),
            'student-1',
            NotificationType::WARNING,
            'تحذير',
            'هذا تحذير مهم',
            NotificationChannel::EMAIL,
        );
        $notification->releaseEvents();

        $notification->markAsRead();

        $this->assertTrue($notification->isRead());
    }

    public function test_generate_id_creates_valid_uuid(): void
    {
        $id = NotificationId::generate();

        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $id->value());
    }

    public function test_dto_conversion(): void
    {
        $id = NotificationId::generate();

        $notification = Notification::create(
            $id,
            'student-1',
            NotificationType::ERROR,
            'خطأ',
            'حدث خطأ غير متوقع',
            NotificationChannel::SMS,
            '/error/123',
        );
        $notification->releaseEvents();

        $mapper = new NotificationMapper;
        $dto = $mapper->toDto($notification);

        $this->assertInstanceOf(NotificationDto::class, $dto);
        $this->assertSame($id->value(), $dto->id);
        $this->assertSame('student-1', $dto->studentId);
        $this->assertSame('error', $dto->type);
        $this->assertSame('خطأ', $dto->title);
        $this->assertSame('حدث خطأ غير متوقع', $dto->message);
        $this->assertSame('sms', $dto->channel);
        $this->assertSame('/error/123', $dto->link);
        $this->assertFalse($dto->isRead);
    }
}
