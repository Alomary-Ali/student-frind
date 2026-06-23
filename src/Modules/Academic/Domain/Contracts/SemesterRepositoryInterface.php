<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\Semester;
use Modules\Academic\Domain\ValueObjects\SemesterId;

interface SemesterRepositoryInterface
{
    public function findById(SemesterId $id): ?Semester;

    public function save(Semester $semester): void;

    /** @return list<Semester> */
    public function findAllActive(): array;
}
