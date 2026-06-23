<?php
declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts;

use Modules\StudentServices\Domain\Entities\DocumentRequest;
use Modules\StudentServices\Domain\ValueObjects\DocumentRequestId;

interface DocumentRequestRepositoryInterface
{
    public function findById(DocumentRequestId $id): ?DocumentRequest;
    public function findByStudentId(string $studentId): array;
    public function save(DocumentRequest $request): void;
}
