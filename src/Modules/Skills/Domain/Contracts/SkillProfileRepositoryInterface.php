<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Contracts;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;

interface SkillProfileRepositoryInterface
{
    public function findById(SkillProfileId $id): ?SkillProfile;

    public function findByStudentId(StudentId $studentId): ?SkillProfile;

    public function save(SkillProfile $profile): void;

    public function delete(SkillProfileId $id): void;
}
