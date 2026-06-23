<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Contracts;

use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use Modules\Academic\Domain\ValueObjects\StudentId;

interface LearningPathRepositoryInterface
{
    public function findById(LearningPathId $id): ?LearningPath;

    /** @return array<LearningPath> */
    public function findByStudentId(StudentId $studentId): array;

    public function save(LearningPath $learningPath): void;

    public function delete(LearningPathId $id): void;
}
