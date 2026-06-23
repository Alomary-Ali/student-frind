<?php

declare(strict_types=1);

namespace Modules\Productivity\Domain\Contracts;

use Modules\Productivity\Domain\Entities\Exam;
use Modules\Productivity\Domain\ValueObjects\ExamId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface ExamRepositoryInterface
{
    public function save(Exam $exam): void;
    public function findById(ExamId $id): ?Exam;
    public function findByUserId(UserId $userId): array;
    public function findUpcomingByUserId(UserId $userId, int $days = 7): array;
    public function delete(ExamId $id): void;
}
