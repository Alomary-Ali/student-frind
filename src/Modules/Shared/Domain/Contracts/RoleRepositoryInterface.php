<?php

declare(strict_types=1);

namespace Modules\Shared\Domain\Contracts;

use Modules\Shared\Domain\Entities\Role;
use Modules\Shared\Domain\Enums\Role as RoleEnum;
use Modules\Shared\Domain\ValueObjects\RoleId;

interface RoleRepositoryInterface
{
    public function findById(RoleId $id): ?Role;

    public function findByName(RoleEnum $name): ?Role;

    /**
     * @return array<Role>
     */
    public function findAll(): array;

    public function save(Role $role): void;

    public function delete(RoleId $id): void;

    /**
     * @param array<string> $userIds
     * @return array<Role>
     */
    public function findByUserIds(array $userIds): array;
}
