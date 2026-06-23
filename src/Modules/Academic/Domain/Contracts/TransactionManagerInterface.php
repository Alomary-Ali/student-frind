<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

interface TransactionManagerInterface
{
    /**
     * @template T
     *
     * @param  callable(): T  $callback
     * @return T
     */
    public function runInTransaction(callable $callback): mixed;
}
