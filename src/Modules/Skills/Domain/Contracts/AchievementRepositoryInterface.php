<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Contracts;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\ValueObjects\AchievementId;

interface AchievementRepositoryInterface
{
    public function findById(AchievementId $id): ?Achievement;

    /** @return array<Achievement> */
    public function findByStudentId(StudentId $studentId): array;

    public function save(Achievement $achievement): void;

    public function delete(AchievementId $id): void;
}
