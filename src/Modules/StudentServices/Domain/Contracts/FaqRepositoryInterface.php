<?php
declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts;

use Modules\StudentServices\Domain\Entities\FAQ;

interface FaqRepositoryInterface
{
    public function findAll(): array;
    public function findByCategory(string $categoryId): array;
    public function save(FAQ $faq): void;
    public function delete(string $id): void;
}
