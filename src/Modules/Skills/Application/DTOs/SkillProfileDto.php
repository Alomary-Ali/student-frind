<?php

declare(strict_types=1);

namespace Modules\Skills\Application\DTOs;

final readonly class SkillProfileDto
{
    /**
     * @param array<SkillDto> $skills
     * @param array<CertificationDto> $certifications
     */
    public function __construct(
        public string $id,
        public string $studentId,
        public array $skills,
        public array $certifications,
    ) {}
}
