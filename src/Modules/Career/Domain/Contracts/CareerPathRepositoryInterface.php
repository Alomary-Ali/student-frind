<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Contracts;

use Modules\Career\Domain\Entities\CareerPath;
use Modules\Career\Domain\ValueObjects\CareerPathId;

interface CareerPathRepositoryInterface
{
    public function findById(CareerPathId $id): ?CareerPath;

    public function findAll(): array;

    public function findByTargetRole(string $targetRole): array;

    public function save(CareerPath $careerPath): void;

    public function delete(CareerPathId $id): void;
}
