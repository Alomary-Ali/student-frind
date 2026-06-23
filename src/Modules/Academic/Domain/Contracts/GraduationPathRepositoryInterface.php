<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\GraduationPath;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface GraduationPathRepositoryInterface
{
    public function findByStudentId(StudentId $studentId): ?GraduationPath;

    public function save(GraduationPath $path): void;
}
