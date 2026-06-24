<?php

declare(strict_types=1);

namespace Modules\Notifications\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Notifications\Application\UseCases\CreateNotification;
use Modules\Notifications\Application\UseCases\GetStudentNotifications;
use Modules\Notifications\Application\UseCases\MarkNotificationAsRead;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Infrastructure\Persistence\EloquentNotificationRepository;
use PHPUnit\Framework\TestCase;

final class NotificationUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private NotificationRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentNotificationRepository;
    }

    public function test_create_notification_use_case(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notificationDto = $createNotification->execute(
            studentId: 'student-1',
            type: 'success',
            title: 'تم قبول الطلب',
            message: 'تم قبول طلبك بنجاح',
            channel: 'in_app',
            link: '/requests/req-001',
        );

        $this->assertNotNull($notificationDto);
        $this->assertEquals('student-1', $notificationDto->studentId);
        $this->assertEquals('success', $notificationDto->type);
        $this->assertEquals('تم قبول الطلب', $notificationDto->title);
        $this->assertFalse($notificationDto->isRead);
    }

    public function test_get_student_notifications_use_case(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        // Create multiple notifications
        $createNotification->execute('student-1', 'info', 'معلومة 1', 'رسالة 1', 'in_app');
        $createNotification->execute('student-1', 'success', 'نجاح', 'رسالة 2', 'in_app');
        $createNotification->execute('student-1', 'warning', 'تحذير', 'رسالة 3', 'in_app');

        $getNotifications = new GetStudentNotifications(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notifications = $getNotifications->execute('student-1');

        $this->assertCount(3, $notifications);
    }

    public function test_mark_notification_as_read_use_case(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notificationDto = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'in_app');

        $this->assertFalse($notificationDto->isRead);

        $markAsRead = new MarkNotificationAsRead(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $updatedDto = $markAsRead->execute($notificationDto->id, 'student-1');

        $this->assertTrue($updatedDto->isRead);
    }

    public function test_get_notifications_filters_by_student(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        // Create notifications for different students
        $createNotification->execute('student-1', 'info', 'معلومة', 'رسالة', 'in_app');
        $createNotification->execute('student-2', 'info', 'معلومة', 'رسالة', 'in_app');
        $createNotification->execute('student-1', 'success', 'نجاح', 'رسالة', 'in_app');

        $getNotifications = new GetStudentNotifications(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $student1Notifications = $getNotifications->execute('student-1');
        $student2Notifications = $getNotifications->execute('student-2');

        $this->assertCount(2, $student1Notifications);
        $this->assertCount(1, $student2Notifications);
    }

    public function test_mark_as_read_validates_ownership(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notificationDto = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'in_app');

        $markAsRead = new MarkNotificationAsRead(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        // Try to mark as read with wrong student ID
        $this->expectException(\Exception::class);
        $markAsRead->execute($notificationDto->id, 'student-2');
    }

    public function test_create_notification_with_different_channels(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $inApp = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'in_app');
        $email = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'email');
        $sms = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'sms');

        $this->assertEquals('in_app', $inApp->channel);
        $this->assertEquals('email', $email->channel);
        $this->assertEquals('sms', $sms->channel);
    }

    public function test_create_notification_without_link(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notificationDto = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'in_app');

        $this->assertNull($notificationDto->link);
    }
}
