<?php
declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;

final readonly class ListStudentDocuments
{
    public function __construct(
        private DocumentRepositoryInterface $documents,
    ) {}

    public function execute(string $studentId): array
    {
        return $this->documents->findByStudentId($studentId);
    }
}
