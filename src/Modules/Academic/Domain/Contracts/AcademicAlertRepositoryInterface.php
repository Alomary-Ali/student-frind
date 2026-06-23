<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface AcademicAlertRepositoryInterface
{
    public function findById(AlertId $id): ?AcademicAlert;

    public function save(AcademicAlert $alert): void;

    /** @return list<AcademicAlert> */
    public function findByStudentId(StudentId $studentId): array;

    /** @return list<AcademicAlert> */
    public function findUnresolvedByStudentId(StudentId $studentId): array;

    public function existsForStudentAndType(StudentId $studentId, string $alertType): bool;
}
