<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Contracts;

use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\ValueObjects\SkillId;

interface SkillRepositoryInterface
{
    public function findById(SkillId $id): ?Skill;

    public function save(Skill $skill): void;

    public function delete(SkillId $id): void;
}
