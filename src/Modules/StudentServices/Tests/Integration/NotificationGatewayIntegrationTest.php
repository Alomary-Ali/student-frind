<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Modules\StudentServices\Application\UseCases\CreateServiceNotification;
use Modules\StudentServices\Infrastructure\Gateways\NotificationGateway;
use Tests\TestCase;

final class NotificationGatewayIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_gateway_sends_notification(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $gateway = new NotificationGateway(
            $this->createMock(\Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface::class),
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
        );

        $result = $gateway->send(
            studentId: $user->id,
            type: 'success',
            title: 'تم قبول طلبك',
            message: 'تم قبول طلب إثبات القيد بنجاح',
            channel: 'in_app',
            link: '/student-services/requests/req-001',
        );

        $this->assertTrue($result);
    }

    public function test_notification_gateway_handles_different_channels(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $gateway = new NotificationGateway(
            $this->createMock(\Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface::class),
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
        );

        // Test in_app channel
        $result1 = $gateway->send($user->id, 'info', 'معلومة', 'رسالة', 'in_app');
        $this->assertTrue($result1);

        // Test email channel (mocked)
        $result2 = $gateway->send($user->id, 'warning', 'تحذير', 'رسالة', 'email');
        $this->assertTrue($result2);

        // Test sms channel (mocked)
        $result3 = $gateway->send($user->id, 'error', 'خطأ', 'رسالة', 'sms');
        $this->assertTrue($result3);
    }

    public function test_notification_gateway_with_link(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $gateway = new NotificationGateway(
            $this->createMock(\Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface::class),
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
        );

        $result = $gateway->send(
            studentId: $user->id,
            type: 'success',
            title: 'تم الاعتماد',
            message: 'تم اعتماد طلبك',
            channel: 'in_app',
            link: '/student-services/requests/req-001',
        );

        $this->assertTrue($result);
    }

    public function test_create_service_notification_use_case(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $createNotification = new CreateServiceNotification(
            new NotificationGateway(
                $this->createMock(\Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface::class),
                $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            ),
        );

        $result = $createNotification->execute(
            studentId: $user->id,
            type: 'success',
            title: 'تم قبول الطلب',
            message: 'تم قبول طلبك بنجاح',
            channel: 'in_app',
            link: '/requests/req-001',
        );

        $this->assertTrue($result);
    }

    public function test_notification_gateway_error_handling(): void
    {
        $gateway = new NotificationGateway(
            $this->createMock(\Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface::class),
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
        );

        // Test with invalid student ID
        $result = $gateway->send(
            studentId: 'invalid-id',
            type: 'info',
            title: 'عنوان',
            message: 'رسالة',
            channel: 'in_app',
        );

        $this->assertFalse($result);
    }

    public function test_notification_gateway_batch_sending(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);

        $gateway = new NotificationGateway(
            $this->createMock(\Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface::class),
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
        );

        // Send multiple notifications
        $results = [];
        for ($i = 0; $i < 5; $i++) {
            $results[] = $gateway->send(
                studentId: $user->id,
                type: 'info',
                title: "إشعار {$i}",
                message: "رسالة {$i}",
                channel: 'in_app',
            );
        }

        $this->assertCount(5, $results);
        $this->assertTrue(in_array(true, $results, true));
    }
}
