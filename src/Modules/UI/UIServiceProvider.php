<?php

declare(strict_types=1);

namespace Modules\UI;

use Illuminate\Support\ServiceProvider;

final class UIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Presentation/Routes/api.php');
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'ui');
    }
}
