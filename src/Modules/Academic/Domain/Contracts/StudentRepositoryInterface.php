<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\Student;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface StudentRepositoryInterface
{
    public function findById(StudentId $id): ?Student;

    public function findByUserId(string $userId): ?Student;

    public function existsByUserId(string $userId): bool;

    public function save(Student $student): void;
}
