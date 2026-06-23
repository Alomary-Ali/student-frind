<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Contracts;

use Modules\Career\Domain\Entities\Interview;
use Modules\Career\Domain\ValueObjects\InterviewId;

interface InterviewRepositoryInterface
{
    public function findById(InterviewId $id): ?Interview;

    public function findByStudentId(string $studentId): array;

    public function save(Interview $interview): void;

    public function delete(InterviewId $id): void;
}
