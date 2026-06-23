<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Contracts;

use Modules\CareerProfile\Domain\Entities\Experience;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;

interface ExperienceRepositoryInterface
{
    public function findById(ExperienceId $id): ?Experience;

    public function save(Experience $experience): void;

    public function delete(ExperienceId $id): void;
}
