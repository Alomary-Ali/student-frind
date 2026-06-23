<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Contracts;

use Modules\CareerProfile\Domain\Entities\Resume;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;

interface ResumeRepositoryInterface
{
    public function findById(ResumeId $id): ?Resume;

    public function save(Resume $resume): void;

    public function delete(ResumeId $id): void;
}
