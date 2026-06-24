<?php

declare(strict_types=1);

namespace Modules\Notifications\Tests;

use DateTimeImmutable;
use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\Enums\NotificationChannel;
use Modules\Notifications\Domain\Enums\NotificationType;
use Modules\Notifications\Domain\Events\NotificationCreated;
use Modules\Notifications\Domain\ValueObjects\NotificationId;
use PHPUnit\Framework\TestCase;

final class NotificationEntityTest extends TestCase
{
    public function test_create_returns_notification_with_unread_status(): void
    {
        $id = NotificationId::generate();
        $notification = Notification::create(
            $id,
            'student-1',
            NotificationType::SUCCESS,
            'تم قبول الطلب',
            'تم قبول طلبك بنجاح',
            NotificationChannel::IN_APP,
            '/requests/req-001',
        );

        $this->assertSame($id, $notification->id());
        $this->assertSame('student-1', $notification->studentId());
        $this->assertSame(NotificationType::SUCCESS, $notification->type());
        $this->assertSame('تم قبول الطلب', $notification->title());
        $this->assertSame('تم قبول طلبك بنجاح', $notification->message());
        $this->assertSame(NotificationChannel::IN_APP, $notification->channel());
        $this->assertSame('/requests/req-001', $notification->link());
        $this->assertFalse($notification->isRead());
    }

    public function test_create_dispatches_notification_created_event(): void
    {
        $id = NotificationId::generate();
        $notification = Notification::create(
            $id,
            'student-1',
            NotificationType::INFO,
            'معلومة',
            'رسالة',
            NotificationChannel::IN_APP,
        );

        $events = $notification->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(NotificationCreated::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->notificationId);
        $this->assertSame('student-1', $events[0]->studentId);
    }

    public function test_mark_as_read_changes_status(): void
    {
        $notification = Notification::create(
            NotificationId::generate(),
            'student-1',
            NotificationType::WARNING,
            'تحذير',
            'رسالة',
            NotificationChannel::IN_APP,
        );

        $this->assertFalse($notification->isRead());

        $notification->markAsRead();

        $this->assertTrue($notification->isRead());
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = NotificationId::generate();
        $now = new DateTimeImmutable;

        $notification = Notification::reconstitute(
            id: $id,
            studentId: 'student-1',
            type: NotificationType::ERROR,
            title: 'خطأ',
            message: 'حدث خطأ',
            channel: NotificationChannel::EMAIL,
            link: null,
            isRead: true,
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id->value(), $notification->id()->value());
        $this->assertSame(NotificationType::ERROR, $notification->type());
        $this->assertSame(NotificationChannel::EMAIL, $notification->channel());
        $this->assertTrue($notification->isRead());
    }

    public function test_release_events_clears_events(): void
    {
        $notification = Notification::create(
            NotificationId::generate(),
            'student-1',
            NotificationType::SUCCESS,
            'عنوان',
            'رسالة',
            NotificationChannel::IN_APP,
        );

        $this->assertCount(1, $notification->releaseEvents());
        $this->assertCount(0, $notification->releaseEvents());
    }

    public function test_create_without_link(): void
    {
        $notification = Notification::create(
            NotificationId::generate(),
            'student-1',
            NotificationType::INFO,
            'عنوان',
            'رسالة',
            NotificationChannel::IN_APP,
        );

        $this->assertNull($notification->link());
    }

    public function test_different_notification_types(): void
    {
        $types = [
            NotificationType::INFO,
            NotificationType::SUCCESS,
            NotificationType::WARNING,
            NotificationType::ERROR,
        ];

        foreach ($types as $type) {
            $notification = Notification::create(
                NotificationId::generate(),
                'student-1',
                $type,
                'عنوان',
                'رسالة',
                NotificationChannel::IN_APP,
            );

            $this->assertSame($type, $notification->type());
        }
    }

    public function test_different_notification_channels(): void
    {
        $channels = [
            NotificationChannel::IN_APP,
            NotificationChannel::EMAIL,
            NotificationChannel::SMS,
        ];

        foreach ($channels as $channel) {
            $notification = Notification::create(
                NotificationId::generate(),
                'student-1',
                NotificationType::INFO,
                'عنوان',
                'رسالة',
                $channel,
            );

            $this->assertSame($channel, $notification->channel());
        }
    }
}
