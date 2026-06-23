<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Domain\Services;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\Services\AchievementUnlocker;
use Modules\Skills\Domain\Services\LearningPathGenerator;
use Modules\Skills\Domain\Services\SkillGapAnalyzer;
use Modules\Skills\Domain\ValueObjects\AchievementId;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use PHPUnit\Framework\TestCase;

final class SkillsServicesTest extends TestCase
{
    public function test_skill_gap_analyzer_returns_missing_skills(): void
    {
        $profile = $this->createProfileWithSkills(['HTML', 'CSS']);
        $analyzer = new SkillGapAnalyzer;

        $result = $analyzer->analyze($profile, 'frontend_developer');

        $this->assertSame('frontend_developer', $result['role']);
        $this->assertContains('HTML', $result['current_skills']);
        $this->assertContains('CSS', $result['current_skills']);
        $this->assertContains('JavaScript', $result['missing_skills']);
        $this->assertNotEquals(100, $result['matching_percentage']);
    }

    public function test_skill_gap_analyzer_returns_100_percent_when_all_match(): void
    {
        $profile = $this->createProfileWithSkills(['HTML', 'CSS', 'JavaScript', 'React', 'Git', 'TypeScript', 'TailwindCSS']);
        $analyzer = new SkillGapAnalyzer;

        $result = $analyzer->analyze($profile, 'frontend_developer');

        $this->assertEmpty($result['missing_skills']);
        $this->assertSame(100, $result['matching_percentage']);
    }

    public function test_skill_gap_analyzer_returns_zero_for_unknown_role(): void
    {
        $profile = $this->createProfileWithSkills(['PHP']);
        $analyzer = new SkillGapAnalyzer;

        $result = $analyzer->analyze($profile, 'unknown_role');

        $this->assertSame(0, $result['matching_percentage']);
        $this->assertEmpty($result['missing_skills']);
    }

    public function test_get_roles_returns_all_roles(): void
    {
        $roles = SkillGapAnalyzer::getRoles();

        $this->assertArrayHasKey('frontend_developer', $roles);
        $this->assertArrayHasKey('backend_developer', $roles);
        $this->assertArrayHasKey('fullstack_developer', $roles);
        $this->assertArrayHasKey('data_scientist', $roles);
        $this->assertArrayHasKey('cybersecurity_analyst', $roles);
        $this->assertCount(5, $roles);
    }

    public function test_learning_path_generator_creates_path_with_steps(): void
    {
        $generator = new LearningPathGenerator;
        $missingSkills = ['PHP', 'Laravel', 'SQL'];

        $path = $generator->generate(
            LearningPathId::generate(),
            StudentId::generate(),
            'backend_developer',
            $missingSkills,
        );

        $this->assertSame('backend_developer', $path->targetRole());
        $this->assertCount(4, $path->steps());
        $this->assertSame('step-1', $path->steps()[0]['id']);
        $this->assertFalse($path->steps()[0]['completed']);
        $this->assertStringContainsString('PHP', $path->steps()[0]['title']);
        $this->assertSame(0, $path->progress());
    }

    public function test_learning_path_generator_uses_fallback_role_label(): void
    {
        $generator = new LearningPathGenerator;

        $path = $generator->generate(
            LearningPathId::generate(),
            StudentId::generate(),
            'some_unknown_role',
            ['Skill1'],
        );

        $this->assertStringContainsString('some_unknown_role', $path->title());
    }

    public function test_achievement_unlocker_unlocks_academic_star(): void
    {
        $studentId = StudentId::generate();
        $unlocker = new AchievementUnlocker;

        $unlocked = $unlocker->checkAndUnlock(
            $studentId, [], null, 5, 0, 0,
        );

        $this->assertCount(1, $unlocked);
        $this->assertSame('النجم الأكاديمي', $unlocked[0]->title());
    }

    public function test_achievement_unlocker_unlocks_multiple(): void
    {
        $studentId = StudentId::generate();
        $profile = $this->createProfileWithSkills(['S1', 'S2', 'S3', 'S4', 'S5', 'S6']);
        $unlocker = new AchievementUnlocker;

        $unlocked = $unlocker->checkAndUnlock(
            $studentId, [], $profile, 5, 10, 0,
        );

        $this->assertCount(3, $unlocked);
        $titles = array_map(fn (Achievement $a) => $a->title(), $unlocked);
        $this->assertContains('النجم الأكاديمي', $titles);
        $this->assertContains('سيد الإنتاجية', $titles);
        $this->assertContains('جامع المهارات', $titles);
    }

    public function test_achievement_unlocker_does_not_unlock_existing(): void
    {
        $studentId = StudentId::generate();
        $existing = [
            Achievement::create(
                AchievementId::generate(), $studentId,
                AchievementType::ACADEMIC, 'النجم الأكاديمي', 'Already earned',
            ),
        ];
        $unlocker = new AchievementUnlocker;

        $unlocked = $unlocker->checkAndUnlock(
            $studentId, $existing, null, 5, 0, 0,
        );

        $this->assertEmpty($unlocked);
    }

    public function test_achievement_unlocker_unlocks_certified_specialist(): void
    {
        $studentId = StudentId::generate();
        $profile = SkillProfile::create(SkillProfileId::generate(), $studentId);
        $profile->addSkill(SkillId::generate(), 'S1', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED);
        $profile->addCertification(
            CertificationId::generate(), 'AWS Certified', 'Amazon',
            new DateTimeImmutable('2026-01-15'),
        );
        $profile->releaseEvents();

        $unlocker = new AchievementUnlocker;

        $unlocked = $unlocker->checkAndUnlock(
            $studentId, [], $profile, 5, 10, 0,
        );

        $titles = array_map(fn (Achievement $a) => $a->title(), $unlocked);
        $this->assertContains('الأخصائي المعتمد', $titles);
    }

    private function createProfileWithSkills(array $skillNames): SkillProfile
    {
        $profileId = SkillProfileId::generate();
        $studentId = StudentId::generate();
        $profile = SkillProfile::create($profileId, $studentId);

        foreach ($skillNames as $name) {
            $profile->addSkill(SkillId::generate(), $name, SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE);
        }

        return $profile;
    }
}
