<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;

final class SkillEntityTest extends TestCase
{
    public function test_create_returns_skill_with_defaults(): void
    {
        $id = SkillId::generate();
        $profileId = SkillProfileId::generate();

        $skill = Skill::create($id, $profileId, 'Laravel', SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE);

        $this->assertSame($id, $skill->id());
        $this->assertSame($profileId, $skill->skillProfileId());
        $this->assertSame('Laravel', $skill->name());
        $this->assertSame(SkillCategory::PROGRAMMING, $skill->category());
        $this->assertSame(SkillLevel::INTERMEDIATE, $skill->level());
        $this->assertSame(0, $skill->yearsOfExperience());
        $this->assertInstanceOf(DateTimeImmutable::class, $skill->lastUsed());
    }

    public function test_create_with_experience(): void
    {
        $id = SkillId::generate();
        $profileId = SkillProfileId::generate();
        $lastUsed = new DateTimeImmutable('2026-01-15');

        $skill = Skill::create($id, $profileId, 'React', SkillCategory::DESIGN, SkillLevel::ADVANCED, 3, $lastUsed);

        $this->assertSame(3, $skill->yearsOfExperience());
        $this->assertSame($lastUsed, $skill->lastUsed());
    }

    public function test_reconstitute_restores_skill(): void
    {
        $id = SkillId::generate();
        $profileId = SkillProfileId::generate();
        $lastUsed = new DateTimeImmutable('2026-01-15');

        $skill = Skill::reconstitute($id, $profileId, 'Python', SkillCategory::AI, SkillLevel::EXPERT, 5, $lastUsed);

        $this->assertSame('Python', $skill->name());
        $this->assertSame(SkillLevel::EXPERT, $skill->level());
        $this->assertSame(5, $skill->yearsOfExperience());
    }

    public function test_update_level_changes_level_and_updates_last_used(): void
    {
        $skill = Skill::create(SkillId::generate(), SkillProfileId::generate(), 'Git', SkillCategory::PROGRAMMING, SkillLevel::BEGINNER);

        $skill->updateLevel(SkillLevel::ADVANCED);
        $this->assertSame(SkillLevel::ADVANCED, $skill->level());
    }

    public function test_increment_experience_increases_years(): void
    {
        $skill = Skill::create(SkillId::generate(), SkillProfileId::generate(), 'Docker', SkillCategory::NETWORKING, SkillLevel::INTERMEDIATE, 2);

        $skill->incrementExperience(3);
        $this->assertSame(5, $skill->yearsOfExperience());
    }

    public function test_increment_experience_default_is_one(): void
    {
        $skill = Skill::create(SkillId::generate(), SkillProfileId::generate(), 'Kubernetes', SkillCategory::NETWORKING, SkillLevel::BEGINNER);

        $skill->incrementExperience();
        $this->assertSame(1, $skill->yearsOfExperience());
    }

    public function test_update_last_used(): void
    {
        $skill = Skill::create(SkillId::generate(), SkillProfileId::generate(), 'CSS', SkillCategory::DESIGN, SkillLevel::INTERMEDIATE);
        $date = new DateTimeImmutable('2026-06-01');

        $skill->updateLastUsed($date);
        $this->assertSame($date, $skill->lastUsed());
    }
}
