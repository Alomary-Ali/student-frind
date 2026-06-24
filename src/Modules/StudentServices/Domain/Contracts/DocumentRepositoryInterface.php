<?php

declare(strict_types=1);

namespace Modules\StudentServices\Domain\Contracts;

use Modules\StudentServices\Domain\Entities\StudentDocument;
use Modules\StudentServices\Domain\ValueObjects\DocumentId;

interface DocumentRepositoryInterface
{
    public function findById(DocumentId $id): ?StudentDocument;

    public function findByStudentId(string $studentId): array;

    public function findByVerificationCode(string $code): ?StudentDocument;

    public function save(StudentDocument $document): void;
}
