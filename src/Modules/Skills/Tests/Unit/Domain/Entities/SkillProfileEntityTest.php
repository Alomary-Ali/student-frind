<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\Events\CertificationEarned;
use Modules\Skills\Domain\Events\SkillAdded;
use Modules\Skills\Domain\Events\SkillLevelUpdated;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use PHPUnit\Framework\TestCase;

final class SkillProfileEntityTest extends TestCase
{
    public function test_create_returns_profile_with_empty_skills_and_certifications(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $this->assertSame($profileId, $profile->id());
        $this->assertSame($studentId, $profile->studentId());
        $this->assertEmpty($profile->skills());
        $this->assertEmpty($profile->certifications());
        $this->assertInstanceOf(DateTimeImmutable::class, $profile->createdAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $profile->updatedAt());
    }

    public function test_reconstitute_restores_profile_with_data(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $now = new DateTimeImmutable;
        $profile = SkillProfile::reconstitute($profileId, $studentId, [], [], $now, $now);

        $this->assertSame($profileId, $profile->id());
        $this->assertSame($studentId, $profile->studentId());
        $this->assertEmpty($profile->skills());
        $this->assertEmpty($profile->certifications());
    }

    public function test_add_skill_adds_skill_and_raises_event(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $skillId = SkillId::generate();
        $profile->addSkill($skillId, 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED, 5);

        $this->assertCount(1, $profile->skills());
        $this->assertSame('PHP', $profile->skills()[0]->name());
        $this->assertSame(SkillCategory::PROGRAMMING, $profile->skills()[0]->category());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(SkillAdded::class, $events[0]);
        $this->assertSame($skillId->value(), $events[0]->skillId);
    }

    public function test_add_skill_duplicate_name_is_ignored(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $profile->addSkill(SkillId::generate(), 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED);
        $profile->addSkill(SkillId::generate(), 'php', SkillCategory::PROGRAMMING, SkillLevel::BEGINNER);

        $this->assertCount(1, $profile->skills());
    }

    public function test_update_skill_level_changes_level_and_raises_event(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $skillId = SkillId::generate();
        $profile->addSkill($skillId, 'PHP', SkillCategory::PROGRAMMING, SkillLevel::BEGINNER);
        $profile->releaseEvents();

        $profile->updateSkillLevel($skillId, SkillLevel::ADVANCED);
        $this->assertSame(SkillLevel::ADVANCED, $profile->skills()[0]->level());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(SkillLevelUpdated::class, $events[0]);
        $this->assertSame(SkillLevel::BEGINNER->value, $events[0]->oldLevel);
        $this->assertSame(SkillLevel::ADVANCED->value, $events[0]->newLevel);
    }

    public function test_update_skill_level_same_level_does_not_raise_event(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $skillId = SkillId::generate();
        $profile->addSkill($skillId, 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED);
        $profile->releaseEvents();

        $profile->updateSkillLevel($skillId, SkillLevel::ADVANCED);
        $events = $profile->releaseEvents();
        $this->assertEmpty($events);
    }

    public function test_add_certification_adds_and_raises_event(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $certId = CertificationId::generate();
        $profile->addCertification($certId, 'OCA Java', 'Oracle', new DateTimeImmutable);

        $this->assertCount(1, $profile->certifications());
        $this->assertSame('OCA Java', $profile->certifications()[0]->name());

        $events = $profile->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CertificationEarned::class, $events[0]);
    }

    public function test_release_events_clears_events(): void
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        $profile->addSkill(SkillId::generate(), 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED);
        $this->assertCount(1, $profile->releaseEvents());
        $this->assertEmpty($profile->releaseEvents());
    }
}
