<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Feature\Infrastructure\Persistence;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Contracts\SkillRepositoryInterface;
use Modules\Skills\Domain\Contracts\CertificationRepositoryInterface;
use Modules\Skills\Domain\Contracts\AchievementRepositoryInterface;
use Modules\Skills\Domain\Contracts\LearningPathRepositoryInterface;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\Entities\Certification;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\AchievementId;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Infrastructure\Persistence\EloquentSkillProfileRepository;
use Modules\Skills\Infrastructure\Persistence\EloquentSkillRepository;
use Modules\Skills\Infrastructure\Persistence\EloquentCertificationRepository;
use Modules\Skills\Infrastructure\Persistence\EloquentAchievementRepository;
use Modules\Skills\Infrastructure\Persistence\EloquentLearningPathRepository;
use Tests\TestCase;

final class SkillsRepositoriesTest extends TestCase
{
    use RefreshDatabase;

    private EloquentStudent $student;
    private StudentId $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->studentId = StudentId::fromString((string) \Illuminate\Support\Str::uuid());
        $this->student = EloquentStudent::create([
            'id' => $this->studentId->value(),
            'user_id' => $user->id,
            'student_number' => 'SKILLS-REPO-' . rand(1000, 9999),
            'academic_status' => 'enrolled',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.5,
        ]);
    }

    public function test_skill_profile_repository_save_and_find(): void
    {
        $repo = app(SkillProfileRepositoryInterface::class);

        $profile = SkillProfile::create(SkillProfileId::generate(), $this->studentId);
        $repo->save($profile);

        $found = $repo->findByStudentId($this->studentId);
        $this->assertNotNull($found);
        $this->assertTrue($profile->id()->equals($found->id()));
    }

    public function test_skill_profile_repository_save_with_skills_and_certifications(): void
    {
        $repo = app(SkillProfileRepositoryInterface::class);

        $profile = SkillProfile::create(SkillProfileId::generate(), $this->studentId);
        $profile->addSkill(SkillId::generate(), 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED, 3);
        $profile->addCertification(
            CertificationId::generate(), 'AWS', 'Amazon',
            new DateTimeImmutable('2026-01-15'), new DateTimeImmutable('2028-01-15')
        );
        $repo->save($profile);

        $found = $repo->findByStudentId($this->studentId);
        $this->assertNotNull($found);
        $this->assertCount(1, $found->skills());
        $this->assertCount(1, $found->certifications());
        $this->assertSame('PHP', $found->skills()[0]->name());
    }

    public function test_skill_profile_repository_delete(): void
    {
        $repo = app(SkillProfileRepositoryInterface::class);

        $profile = SkillProfile::create(SkillProfileId::generate(), $this->studentId);
        $repo->save($profile);

        $repo->delete($profile->id());
        $this->assertNull($repo->findById($profile->id()));
    }

    public function test_skill_repository_save_and_find(): void
    {
        $profileRepo = app(SkillProfileRepositoryInterface::class);
        $profile = SkillProfile::create(SkillProfileId::generate(), $this->studentId);
        $profileRepo->save($profile);

        $skillRepo = app(SkillRepositoryInterface::class);
        $skill = Skill::create(SkillId::generate(), $profile->id(), 'Laravel', SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE, 2);
        $skillRepo->save($skill);

        $found = $skillRepo->findById($skill->id());
        $this->assertNotNull($found);
        $this->assertSame('Laravel', $found->name());
    }

    public function test_skill_repository_delete(): void
    {
        $profileRepo = app(SkillProfileRepositoryInterface::class);
        $profile = SkillProfile::create(SkillProfileId::generate(), $this->studentId);
        $profileRepo->save($profile);

        $skillRepo = app(SkillRepositoryInterface::class);
        $skill = Skill::create(SkillId::generate(), $profile->id(), 'React', SkillCategory::DESIGN, SkillLevel::BEGINNER);
        $skillRepo->save($skill);

        $skillRepo->delete($skill->id());
        $this->assertNull($skillRepo->findById($skill->id()));
    }

    public function test_certification_repository_save_and_find(): void
    {
        $profileRepo = app(SkillProfileRepositoryInterface::class);
        $profile = SkillProfile::create(SkillProfileId::generate(), $this->studentId);
        $profileRepo->save($profile);

        $certRepo = app(CertificationRepositoryInterface::class);
        $cert = Certification::create(
            CertificationId::generate(), $profile->id(), 'OCA Java', 'Oracle',
            new DateTimeImmutable('2026-01-15')
        );
        $certRepo->save($cert);

        $found = $certRepo->findById($cert->id());
        $this->assertNotNull($found);
        $this->assertSame('OCA Java', $found->name());
    }

    public function test_certification_repository_delete(): void
    {
        $profileRepo = app(SkillProfileRepositoryInterface::class);
        $profile = SkillProfile::create(SkillProfileId::generate(), $this->studentId);
        $profileRepo->save($profile);

        $certRepo = app(CertificationRepositoryInterface::class);
        $cert = Certification::create(
            CertificationId::generate(), $profile->id(), 'GCP', 'Google',
            new DateTimeImmutable('2026-02-01')
        );
        $certRepo->save($cert);

        $certRepo->delete($cert->id());
        $this->assertNull($certRepo->findById($cert->id()));
    }

    public function test_achievement_repository_save_and_find(): void
    {
        $repo = app(AchievementRepositoryInterface::class);

        $achievement = Achievement::create(
            AchievementId::generate(), $this->studentId, AchievementType::ACADEMIC,
            'النجم الأكاديمي', 'إكمال 5 مساقات'
        );
        $repo->save($achievement);

        $found = $repo->findById($achievement->id());
        $this->assertNotNull($found);
        $this->assertSame('النجم الأكاديمي', $found->title());
    }

    public function test_achievement_repository_find_by_student_id(): void
    {
        $repo = app(AchievementRepositoryInterface::class);

        $a1 = Achievement::create(AchievementId::generate(), $this->studentId, AchievementType::ACADEMIC, 'A1', 'Desc');
        $a2 = Achievement::create(AchievementId::generate(), $this->studentId, AchievementType::CAREER, 'A2', 'Desc');
        $repo->save($a1);
        $repo->save($a2);

        $achievements = $repo->findByStudentId($this->studentId);
        $this->assertCount(2, $achievements);
    }

    public function test_achievement_repository_delete(): void
    {
        $repo = app(AchievementRepositoryInterface::class);
        $achievement = Achievement::create(AchievementId::generate(), $this->studentId, AchievementType::COMMUNITY, 'T', 'D');
        $repo->save($achievement);

        $repo->delete($achievement->id());
        $this->assertNull($repo->findById($achievement->id()));
    }

    public function test_learning_path_repository_save_and_find(): void
    {
        $repo = app(LearningPathRepositoryInterface::class);

        $path = LearningPath::create(LearningPathId::generate(), $this->studentId, 'Test Path', 'backend_developer');
        $repo->save($path);

        $found = $repo->findById($path->id());
        $this->assertNotNull($found);
        $this->assertSame('Test Path', $found->title());
    }

    public function test_learning_path_repository_find_by_student_id(): void
    {
        $repo = app(LearningPathRepositoryInterface::class);

        $p1 = LearningPath::create(LearningPathId::generate(), $this->studentId, 'Path 1', 'backend_developer');
        $p2 = LearningPath::create(LearningPathId::generate(), $this->studentId, 'Path 2', 'frontend_developer');
        $repo->save($p1);
        $repo->save($p2);

        $paths = $repo->findByStudentId($this->studentId);
        $this->assertCount(2, $paths);
    }

    public function test_learning_path_repository_delete(): void
    {
        $repo = app(LearningPathRepositoryInterface::class);
        $path = LearningPath::create(LearningPathId::generate(), $this->studentId, 'To Delete', 'role');
        $repo->save($path);

        $repo->delete($path->id());
        $this->assertNull($repo->findById($path->id()));
    }
}
