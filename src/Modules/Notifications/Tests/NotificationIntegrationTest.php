<?php

declare(strict_types=1);

namespace Modules\Notifications\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Notifications\Application\UseCases\CreateNotification;
use Modules\Notifications\Application\UseCases\GetStudentNotifications;
use Modules\Notifications\Application\UseCases\MarkNotificationAsRead;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Infrastructure\Persistence\EloquentNotification;
use Modules\Notifications\Infrastructure\Persistence\EloquentNotificationRepository;
use PHPUnit\Framework\TestCase;

final class NotificationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private NotificationRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentNotificationRepository;
    }

    public function test_notification_creation_and_retrieval_flow(): void
    {
        // Step 1: Create notification
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

        // Step 2: Retrieve from database
        $retrieved = $this->repository->findById(
            \Modules\Notifications\Domain\ValueObjects\NotificationId::fromString($notificationDto->id),
        );

        $this->assertNotNull($retrieved);
        $this->assertEquals($notificationDto->id, $retrieved->id()->value());
        $this->assertEquals('student-1', $retrieved->studentId());

        // Step 3: Mark as read
        $markAsRead = new MarkNotificationAsRead(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $updatedDto = $markAsRead->execute($notificationDto->id, 'student-1');

        $this->assertTrue($updatedDto->isRead);
    }

    public function test_notification_persistence(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notificationDto = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'in_app');

        // Check database
        $this->assertDatabaseHas('notifications', [
            'id' => $notificationDto->id,
            'student_id' => 'student-1',
            'type' => 'info',
            'title' => 'عنوان',
            'is_read' => false,
        ]);
    }

    public function test_multiple_notifications_for_student(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        // Create 5 notifications
        for ($i = 0; $i < 5; $i++) {
            $createNotification->execute('student-1', 'info', "عنوان {$i}", "رسالة {$i}", 'in_app');
        }

        $getNotifications = new GetStudentNotifications(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notifications = $getNotifications->execute('student-1');

        $this->assertCount(5, $notifications);
    }

    public function test_notification_mark_as_read_persistence(): void
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

        $markAsRead->execute($notificationDto->id, 'student-1');

        // Check database
        $this->assertDatabaseHas('notifications', [
            'id' => $notificationDto->id,
            'is_read' => true,
        ]);
    }

    public function test_notification_filtering_by_student(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        // Create notifications for different students
        $createNotification->execute('student-1', 'info', 'معلومة 1', 'رسالة 1', 'in_app');
        $createNotification->execute('student-2', 'info', 'معلومة 2', 'رسالة 2', 'in_app');
        $createNotification->execute('student-1', 'success', 'نجاح', 'رسالة 3', 'in_app');
        $createNotification->execute('student-3', 'warning', 'تحذير', 'رسالة 4', 'in_app');

        $getNotifications = new GetStudentNotifications(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $student1Notifications = $getNotifications->execute('student-1');
        $student2Notifications = $getNotifications->execute('student-2');
        $student3Notifications = $getNotifications->execute('student-3');

        $this->assertCount(2, $student1Notifications);
        $this->assertCount(1, $student2Notifications);
        $this->assertCount(1, $student3Notifications);
    }

    public function test_notification_different_types(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $types = ['info', 'success', 'warning', 'error'];

        foreach ($types as $type) {
            $createNotification->execute('student-1', $type, 'عنوان', 'رسالة', 'in_app');
        }

        $getNotifications = new GetStudentNotifications(
            $this->repository,
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $notifications = $getNotifications->execute('student-1');

        $this->assertCount(4, $notifications);
    }

    public function test_notification_with_and_without_link(): void
    {
        $createNotification = new CreateNotification(
            $this->repository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\Notifications\Application\Mappers\NotificationMapper,
        );

        $withLink = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'in_app', '/link');
        $withoutLink = $createNotification->execute('student-1', 'info', 'عنوان', 'رسالة', 'in_app');

        $this->assertEquals('/link', $withLink->link);
        $this->assertNull($withoutLink->link);
    }

    public function test_eloquent_model_mapping(): void
    {
        $eloquent = EloquentNotification::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'student_id' => 'student-1',
            'type' => 'success',
            'title' => 'عنوان',
            'message' => 'رسالة',
            'channel' => 'in_app',
            'link' => '/link',
            'is_read' => false,
        ]);

        $repository = new EloquentNotificationRepository;
        $notification = $repository->findById(
            \Modules\Notifications\Domain\ValueObjects\NotificationId::fromString($eloquent->id),
        );

        $this->assertNotNull($notification);
        $this->assertEquals($eloquent->id, $notification->id()->value());
        $this->assertEquals($eloquent->student_id, $notification->studentId());
    }
}
