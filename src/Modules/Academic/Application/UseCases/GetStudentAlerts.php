<?php

declare(strict_types=1);

namespace Modules\Academic\Application\UseCases;

use Modules\Academic\Application\DTOs\AcademicAlertDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Domain\Contracts\AcademicAlertRepositoryInterface;
use Modules\Academic\Domain\ValueObjects\StudentId;

final readonly class GetStudentAlerts
{
    public function __construct(
        private AcademicAlertRepositoryInterface $alerts,
        private AcademicMapper $mapper,
    ) {}

    /**
     * Get all alerts for a student.
     *
     * @return list<AcademicAlertDto>
     */
    public function execute(string $studentId): array
    {
        $id = StudentId::fromString($studentId);
        $alerts = $this->alerts->findByStudentId($id);

        return array_map(
            fn ($alert) => $this->mapper->toAcademicAlertDto($alert),
            $alerts,
        );
    }

    /**
     * Get unresolved alerts for a student.
     *
     * @return list<AcademicAlertDto>
     */
    public function executeUnresolved(string $studentId): array
    {
        $id = StudentId::fromString($studentId);
        $alerts = $this->alerts->findUnresolvedByStudentId($id);

        return array_map(
            fn ($alert) => $this->mapper->toAcademicAlertDto($alert),
            $alerts,
        );
    }
}
