<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Events\SkillAdded;
use Modules\Skills\Domain\Events\SkillLevelUpdated;
use Modules\Skills\Domain\Events\CertificationEarned;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;

final class SkillProfile
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly SkillProfileId $id,
        private readonly StudentId $studentId,
        private array $skills,
        private array $certifications,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        SkillProfileId $id,
        StudentId $studentId,
    ): self {
        $now = new DateTimeImmutable();
        return new self(
            $id,
            $studentId,
            [],
            [],
            $now,
            $now
        );
    }

    public static function reconstitute(
        SkillProfileId $id,
        StudentId $studentId,
        array $skills,
        array $certifications,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $skills,
            $certifications,
            $createdAt,
            $updatedAt
        );
    }

    public function id(): SkillProfileId
    {
        return $this->id;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }

    /** @return array<Skill> */
    public function skills(): array
    {
        return $this->skills;
    }

    /** @return array<Certification> */
    public function certifications(): array
    {
        return $this->certifications;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function addSkill(
        SkillId $id,
        string $name,
        SkillCategory $category,
        SkillLevel $level,
        int $yearsOfExperience = 0,
    ): void {
        // Check if skill already exists
        foreach ($this->skills as $existingSkill) {
            if (mb_strtolower($existingSkill->name()) === mb_strtolower($name)) {
                return;
            }
        }

        $skill = Skill::create($id, $this->id, $name, $category, $level, $yearsOfExperience);
        $this->skills[] = $skill;
        $this->updatedAt = new DateTimeImmutable();

        $this->raise(new SkillAdded(
            $id->value(),
            $this->id->value(),
            $name,
            $category->value,
            $level->value,
            $this->updatedAt
        ));
    }

    public function updateSkillLevel(SkillId $skillId, SkillLevel $newLevel): void
    {
        foreach ($this->skills as $skill) {
            if ($skill->id()->equals($skillId)) {
                $oldLevel = $skill->level();
                if ($oldLevel !== $newLevel) {
                    $skill->updateLevel($newLevel);
                    $this->updatedAt = new DateTimeImmutable();

                    $this->raise(new SkillLevelUpdated(
                        $skillId->value(),
                        $this->id->value(),
                        $skill->name(),
                        $oldLevel->value,
                        $newLevel->value,
                        $this->updatedAt
                    ));
                }
                break;
            }
        }
    }

    public function addCertification(
        CertificationId $id,
        string $name,
        string $issuer,
        DateTimeImmutable $issueDate,
        ?DateTimeImmutable $expiryDate = null,
        ?string $credentialUrl = null,
        ?string $verificationCode = null,
    ): void {
        $certification = Certification::create(
            $id,
            $this->id,
            $name,
            $issuer,
            $issueDate,
            $expiryDate,
            $credentialUrl,
            $verificationCode
        );

        $this->certifications[] = $certification;
        $this->updatedAt = new DateTimeImmutable();

        $this->raise(new CertificationEarned(
            $id->value(),
            $this->id->value(),
            $name,
            $issuer,
            $this->updatedAt
        ));
    }

    private function raise(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
