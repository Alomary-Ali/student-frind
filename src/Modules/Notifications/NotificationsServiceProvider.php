<?php

declare(strict_types=1);

namespace Modules\Notifications;

use Illuminate\Support\ServiceProvider;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Infrastructure\Persistence\EloquentNotificationRepository;

final class NotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationRepositoryInterface::class, EloquentNotificationRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Http/routes.php');
    }
}
