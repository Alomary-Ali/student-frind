<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Entities;

use DateTimeImmutable;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\ValueObjects\SkillId;

final class Skill
{
    private function __construct(
        private readonly SkillId $id,
        private readonly SkillProfileId $skillProfileId,
        private string $name,
        private SkillCategory $category,
        private SkillLevel $level,
        private int $yearsOfExperience,
        private DateTimeImmutable $lastUsed,
    ) {}

    public static function create(
        SkillId $id,
        SkillProfileId $skillProfileId,
        string $name,
        SkillCategory $category,
        SkillLevel $level,
        int $yearsOfExperience = 0,
        ?DateTimeImmutable $lastUsed = null,
    ): self {
        return new self(
            $id,
            $skillProfileId,
            $name,
            $category,
            $level,
            $yearsOfExperience,
            $lastUsed ?? new DateTimeImmutable()
        );
    }

    public static function reconstitute(
        SkillId $id,
        SkillProfileId $skillProfileId,
        string $name,
        SkillCategory $category,
        SkillLevel $level,
        int $yearsOfExperience,
        DateTimeImmutable $lastUsed,
    ): self {
        return new self(
            $id,
            $skillProfileId,
            $name,
            $category,
            $level,
            $yearsOfExperience,
            $lastUsed
        );
    }

    public function id(): SkillId
    {
        return $this->id;
    }

    public function skillProfileId(): SkillProfileId
    {
        return $this->skillProfileId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): SkillCategory
    {
        return $this->category;
    }

    public function level(): SkillLevel
    {
        return $this->level;
    }

    public function yearsOfExperience(): int
    {
        return $this->yearsOfExperience;
    }

    public function lastUsed(): DateTimeImmutable
    {
        return $this->lastUsed;
    }

    public function updateLevel(SkillLevel $newLevel): void
    {
        $this->level = $newLevel;
        $this->lastUsed = new DateTimeImmutable();
    }

    public function incrementExperience(int $years = 1): void
    {
        $this->yearsOfExperience += $years;
        $this->lastUsed = new DateTimeImmutable();
    }

    public function updateLastUsed(DateTimeImmutable $date): void
    {
        $this->lastUsed = $date;
    }
}
