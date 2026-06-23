<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Contracts;

use Modules\Academic\Domain\Entities\Curriculum;
use Modules\Academic\Domain\ValueObjects\CurriculumId;

interface CurriculumRepositoryInterface
{
    public function findById(CurriculumId $id): ?Curriculum;

    public function save(Curriculum $curriculum): void;
}
