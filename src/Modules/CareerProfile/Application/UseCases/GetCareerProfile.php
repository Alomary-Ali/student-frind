<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;

final readonly class GetCareerProfile
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private CareerProfileMapper $mapper,
    ) {}

    public function execute(string $studentId): ?CareerProfileDto
    {
        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            return null;
        }

        return $this->mapper->toCareerProfileDto($profile);
    }
}
