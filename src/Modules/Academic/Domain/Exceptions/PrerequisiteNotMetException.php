<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Exceptions;

use RuntimeException;

final class PrerequisiteNotMetException extends RuntimeException
{
    public function __construct(
        private readonly string $prerequisiteCourseId,
        private readonly float $minimumGrade
    ) {
        parent::__construct(
            sprintf(
                'Prerequisite course %s not met. Minimum grade required: %.2f',
                $prerequisiteCourseId,
                $minimumGrade
            )
        );
    }

    public function prerequisiteCourseId(): string
    {
        return $this->prerequisiteCourseId;
    }

    public function minimumGrade(): float
    {
        return $this->minimumGrade;
    }
}
