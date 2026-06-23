<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\ProductivitySnapshot;
use Modules\Productivity\Domain\ValueObjects\ProductivitySnapshotId;

interface ProductivitySnapshotRepositoryInterface
{
    public function findById(ProductivitySnapshotId $id): ?ProductivitySnapshot;

    public function findByUserId(string $userId): array;

    public function findLatestByUserId(string $userId): ?ProductivitySnapshot;

    public function save(ProductivitySnapshot $snapshot): void;

    public function delete(ProductivitySnapshotId $id): void;
}
