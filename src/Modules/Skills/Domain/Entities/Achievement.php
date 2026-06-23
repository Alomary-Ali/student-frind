<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\ValueObjects\AchievementId;

final class Achievement
{
    private function __construct(
        private readonly AchievementId $id,
        private readonly StudentId $studentId,
        private AchievementType $type,
        private string $title,
        private string $description,
        private ?string $badgeUrl,
        private DateTimeImmutable $unlockedAt,
    ) {}

    public static function create(
        AchievementId $id,
        StudentId $studentId,
        AchievementType $type,
        string $title,
        string $description,
        ?string $badgeUrl = null,
    ): self {
        return new self(
            $id,
            $studentId,
            $type,
            $title,
            $description,
            $badgeUrl,
            new DateTimeImmutable,
        );
    }

    public static function reconstitute(
        AchievementId $id,
        StudentId $studentId,
        AchievementType $type,
        string $title,
        string $description,
        ?string $badgeUrl,
        DateTimeImmutable $unlockedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $type,
            $title,
            $description,
            $badgeUrl,
            $unlockedAt,
        );
    }

    public function id(): AchievementId
    {
        return $this->id;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }

    public function type(): AchievementType
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function badgeUrl(): ?string
    {
        return $this->badgeUrl;
    }

    public function unlockedAt(): DateTimeImmutable
    {
        return $this->unlockedAt;
    }
}
