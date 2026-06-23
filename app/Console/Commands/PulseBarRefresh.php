<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\PulseBarDataResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

final class PulseBarRefresh extends Command
{
    protected $signature = 'pulsebar:refresh
        {user? : The user ID to refresh (omit to clear all)}';

    protected $description = 'Refresh pulsebar cached data for a user';

    public function handle(PulseBarDataResolver $resolver): int
    {
        $userId = $this->argument('user');

        if ($userId) {
            $resolver->refresh($userId);
            $this->info("Pulsebar cache refreshed for user: {$userId}");
        } else {
            Cache::flush();
            $this->info('Pulsebar cache cleared for all users');
        }

        return self::SUCCESS;
    }
}
