<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Integrations;

use Illuminate\Support\Facades\DB;
use Modules\Academic\Domain\Contracts\TransactionManagerInterface;

final class LaravelTransactionManager implements TransactionManagerInterface
{
    public function runInTransaction(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
