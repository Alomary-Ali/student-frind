<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Contracts;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;

interface CareerProfileRepositoryInterface
{
    public function findById(CareerProfileId $id): ?CareerProfile;

    public function findByStudentId(StudentId $studentId): ?CareerProfile;

    public function save(CareerProfile $profile): void;

    public function delete(CareerProfileId $id): void;
}
