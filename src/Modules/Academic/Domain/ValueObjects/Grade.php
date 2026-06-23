<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\ValueObjects;

use Modules\Academic\Domain\Enums\GradeLetter;
use Modules\Academic\Domain\Exceptions\InvalidGradeException;

final class Grade
{
    private function __construct(
        private readonly GradeLetter $letter,
        private readonly float $gradePoints,
    ) {}

    public static function fromLetter(GradeLetter $letter): self
    {
        return new self($letter, $letter->gradePoints());
    }

    public static function fromValues(string $letter, float $gradePoints): self
    {
        $gradeLetter = GradeLetter::from($letter);

        if (abs($gradeLetter->gradePoints() - $gradePoints) > 0.01) {
            throw InvalidGradeException::pointsMismatch($letter, $gradePoints);
        }

        return new self($gradeLetter, $gradePoints);
    }

    public function letter(): GradeLetter
    {
        return $this->letter;
    }

    public function letterValue(): string
    {
        return $this->letter->value;
    }

    public function gradePoints(): float
    {
        return $this->gradePoints;
    }

    public function isPassing(): bool
    {
        return $this->gradePoints >= 2.0;
    }

    public function equals(self $other): bool
    {
        return $this->letter === $other->letter
            && $this->gradePoints === $other->gradePoints;
    }
}
