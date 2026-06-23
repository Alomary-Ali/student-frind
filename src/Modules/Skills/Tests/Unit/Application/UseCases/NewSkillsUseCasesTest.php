<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Unit\Application\UseCases;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Skills\Application\DTOs\AchievementDto;
use Modules\Skills\Application\DTOs\LearningPathDto;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Application\UseCases\CreateLearningPath;
use Modules\Skills\Application\UseCases\UnlockAchievement;
use Modules\Skills\Application\UseCases\UpdateLearningPathProgress;
use Modules\Skills\Domain\Contracts\AchievementRepositoryInterface;
use Modules\Skills\Domain\Contracts\LearningPathRepositoryInterface;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\ValueObjects\SkillId;

use Modules\Skills\Domain\Services\AchievementUnlocker;
use Modules\Skills\Domain\Services\LearningPathGenerator;
use Modules\Skills\Domain\Services\SkillGapAnalyzer;
use Modules\Skills\Domain\ValueObjects\AchievementId;
use Modules\Skills\Domain\ValueObjects\LearningPathId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;

final class NewSkillsUseCasesTest extends TestCase
{
    private SkillsMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new SkillsMapper();
    }

    public function test_unlock_achievement_creates_achievement_and_dispatches_event(): void
    {
        $studentId = StudentId::generate();
        $studentIdValue = $studentId->value();

        $achievementsRepo = new class implements AchievementRepositoryInterface {
            public array $saved = [];
            public function findById(AchievementId $id): ?Achievement { return null; }
            public function findByStudentId(StudentId $studentId): array { return []; }
            public function save(Achievement $achievement): void { $this->saved[] = $achievement; }
            public function delete(AchievementId $id): void {}
        };

        $profilesRepo = new class implements SkillProfileRepositoryInterface {
            public function findById(SkillProfileId $id): ?SkillProfile { return null; }
            public function findByStudentId(StudentId $studentId): ?SkillProfile { return null; }
            public function save(SkillProfile $profile): void {}
            public function delete(SkillProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->exactly(2))->method('dispatch');

        $unlocker = new AchievementUnlocker();

        $useCase = new UnlockAchievement($achievementsRepo, $profilesRepo, $unlocker, $events, $this->mapper);
        $result = $useCase->execute($studentIdValue, completedCoursesCount: 5, completedTasksCount: 10);

        $this->assertCount(2, $result);
        $this->assertCount(2, $achievementsRepo->saved);
        $this->assertInstanceOf(AchievementDto::class, $result[0]);
        $this->assertSame($studentIdValue, $result[0]->studentId);
        $this->assertSame('النجم الأكاديمي', $result[0]->title);
        $this->assertSame('سيد الإنتاجية', $result[1]->title);
    }

    public function test_unlock_achievement_skips_existing_achievements(): void
    {
        $studentId = StudentId::generate();
        $existingAchievement = Achievement::create(
            AchievementId::generate(), $studentId, AchievementType::ACADEMIC,
            'النجم الأكاديمي', 'وصف', null
        );

        $achievementsRepo = new class($studentId, $existingAchievement) implements AchievementRepositoryInterface {
            private StudentId $sid;
            private Achievement $existing;
            public array $saved = [];
            public function __construct(StudentId $sid, Achievement $existing) {
                $this->sid = $sid;
                $this->existing = $existing;
            }
            public function findById(AchievementId $id): ?Achievement { return null; }
            public function findByStudentId(StudentId $studentId): array {
                return $studentId->equals($this->sid) ? [$this->existing] : [];
            }
            public function save(Achievement $achievement): void { $this->saved[] = $achievement; }
            public function delete(AchievementId $id): void {}
        };

        $profilesRepo = new class implements SkillProfileRepositoryInterface {
            public function findById(SkillProfileId $id): ?SkillProfile { return null; }
            public function findByStudentId(StudentId $studentId): ?SkillProfile { return null; }
            public function save(SkillProfile $profile): void {}
            public function delete(SkillProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->exactly(1))->method('dispatch');

        $unlocker = new AchievementUnlocker();

        $useCase = new UnlockAchievement($achievementsRepo, $profilesRepo, $unlocker, $events, $this->mapper);
        $result = $useCase->execute($studentId->value(), completedCoursesCount: 5, completedTasksCount: 10);

        $this->assertCount(1, $result);
        $this->assertSame('سيد الإنتاجية', $result[0]->title);
    }

    public function test_unlock_achievement_checks_skill_profile_certifications(): void
    {
        $studentId = StudentId::generate();
        $profile = SkillProfile::create(SkillProfileId::generate(), $studentId);
        $profile->addSkill(SkillId::generate(), 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED);
        $profile->addSkill(SkillId::generate(), 'Laravel', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED);
        $profile->addSkill(SkillId::generate(), 'Python', SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE);
        $profile->addSkill(SkillId::generate(), 'SQL', SkillCategory::DATA_ANALYSIS, SkillLevel::INTERMEDIATE);
        $profile->addSkill(SkillId::generate(), 'Git', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED);

        $achievementsRepo = new class implements AchievementRepositoryInterface {
            public function findById(AchievementId $id): ?Achievement { return null; }
            public function findByStudentId(StudentId $studentId): array { return []; }
            public function save(Achievement $achievement): void {}
            public function delete(AchievementId $id): void {}
        };

        $profilesRepo = new class($studentId, $profile) implements SkillProfileRepositoryInterface {
            private StudentId $sid;
            private SkillProfile $profile;
            public function __construct(StudentId $sid, SkillProfile $profile) {
                $this->sid = $sid;
                $this->profile = $profile;
            }
            public function findById(SkillProfileId $id): ?SkillProfile { return null; }
            public function findByStudentId(StudentId $studentId): ?SkillProfile {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }
            public function save(SkillProfile $profile): void {}
            public function delete(SkillProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->atLeast(1))->method('dispatch');

        $unlocker = new AchievementUnlocker();

        $useCase = new UnlockAchievement($achievementsRepo, $profilesRepo, $unlocker, $events, $this->mapper);
        $result = $useCase->execute($studentId->value(), completedCoursesCount: 5, completedTasksCount: 10);

        $titles = array_map(fn(AchievementDto $d) => $d->title, $result);
        $this->assertContains('جامع المهارات', $titles);
    }

    public function test_create_learning_path_creates_and_dispatches_event(): void
    {
        $studentId = StudentId::generate();
        $profile = SkillProfile::create(SkillProfileId::generate(), $studentId);
        $profile->addSkill(
            \Modules\Skills\Domain\ValueObjects\SkillId::generate(),
            'HTML', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED
        );

        $profilesRepo = new class($studentId, $profile) implements SkillProfileRepositoryInterface {
            private StudentId $sid;
            private SkillProfile $profile;
            public function __construct(StudentId $sid, SkillProfile $profile) {
                $this->sid = $sid;
                $this->profile = $profile;
            }
            public function findById(SkillProfileId $id): ?SkillProfile { return null; }
            public function findByStudentId(StudentId $studentId): ?SkillProfile {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }
            public function save(SkillProfile $profile): void {}
            public function delete(SkillProfileId $id): void {}
        };

        $pathsRepo = new class implements LearningPathRepositoryInterface {
            public ?LearningPath $saved = null;
            public function findById(LearningPathId $id): ?LearningPath { return null; }
            public function findByStudentId(StudentId $studentId): array { return []; }
            public function save(LearningPath $learningPath): void { $this->saved = $learningPath; }
            public function delete(LearningPathId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $gapAnalyzer = new SkillGapAnalyzer();
        $pathGenerator = new LearningPathGenerator();

        $useCase = new CreateLearningPath($pathsRepo, $profilesRepo, $gapAnalyzer, $pathGenerator, $events, $this->mapper);
        $dto = $useCase->execute($studentId->value(), 'fullstack_developer');

        $this->assertInstanceOf(LearningPathDto::class, $dto);
        $this->assertSame($studentId->value(), $dto->studentId);
        $this->assertNotNull($pathsRepo->saved);
        $this->assertStringContainsString('مسار التعلم', $dto->title);
        $this->assertStringContainsString('fullstack_developer', $dto->targetRole);
    }

    public function test_create_learning_path_throws_when_no_skill_profile(): void
    {
        $studentId = StudentId::generate();

        $profilesRepo = new class implements SkillProfileRepositoryInterface {
            public function findById(SkillProfileId $id): ?SkillProfile { return null; }
            public function findByStudentId(StudentId $studentId): ?SkillProfile { return null; }
            public function save(SkillProfile $profile): void {}
            public function delete(SkillProfileId $id): void {}
        };

        $pathsRepo = $this->createMock(LearningPathRepositoryInterface::class);
        $events = $this->createMock(EventDispatcherInterface::class);

        $useCase = new CreateLearningPath(
            $pathsRepo, $profilesRepo,
            new SkillGapAnalyzer(), new LearningPathGenerator(),
            $events, $this->mapper
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Skill profile not found');
        $useCase->execute($studentId->value(), 'fullstack_developer');
    }

    public function test_update_learning_path_progress_completes_step(): void
    {
        $studentId = StudentId::generate();
        $steps = [
            ['id' => 'step-1', 'title' => 'أساسيات', 'description' => 'الخطوة الأولى', 'completed' => false, 'completed_at' => null],
            ['id' => 'step-2', 'title' => 'متقدم', 'description' => 'الخطوة الثانية', 'completed' => false, 'completed_at' => null],
        ];
        $path = LearningPath::create(
            LearningPathId::generate(), $studentId, 'Test Path', 'developer', $steps,
            new DateTimeImmutable('+3 months')
        );
        $pathId = $path->id()->value();

        $pathsRepo = new class($path) implements LearningPathRepositoryInterface {
            private LearningPath $path;
            public ?LearningPath $saved = null;
            public function __construct(LearningPath $path) { $this->path = $path; }
            public function findById(LearningPathId $id): ?LearningPath { return $this->path; }
            public function findByStudentId(StudentId $studentId): array { return []; }
            public function save(LearningPath $learningPath): void { $this->saved = $learningPath; }
            public function delete(LearningPathId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);

        $useCase = new UpdateLearningPathProgress($pathsRepo, $events, $this->mapper);
        $dto = $useCase->execute($pathId, completeStepId: 'step-1');

        $this->assertInstanceOf(LearningPathDto::class, $dto);
        $this->assertNotNull($pathsRepo->saved);
        $this->assertSame($pathId, $dto->id);
    }

    public function test_update_learning_path_progress_sets_custom_progress(): void
    {
        $studentId = StudentId::generate();
        $path = LearningPath::create(
            LearningPathId::generate(), $studentId, 'Test Path', 'developer', [],
            new DateTimeImmutable('+3 months')
        );
        $pathId = $path->id()->value();

        $pathsRepo = new class($path) implements LearningPathRepositoryInterface {
            private LearningPath $path;
            public ?LearningPath $saved = null;
            public function __construct(LearningPath $path) { $this->path = $path; }
            public function findById(LearningPathId $id): ?LearningPath { return $this->path; }
            public function findByStudentId(StudentId $studentId): array { return []; }
            public function save(LearningPath $learningPath): void { $this->saved = $learningPath; }
            public function delete(LearningPathId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);

        $useCase = new UpdateLearningPathProgress($pathsRepo, $events, $this->mapper);
        $dto = $useCase->execute($pathId, setProgress: 75);

        $this->assertSame(75, $dto->progress);
    }

    public function test_update_learning_path_progress_throws_when_not_found(): void
    {
        $pathsRepo = new class implements LearningPathRepositoryInterface {
            public function findById(LearningPathId $id): ?LearningPath { return null; }
            public function findByStudentId(StudentId $studentId): array { return []; }
            public function save(LearningPath $learningPath): void {}
            public function delete(LearningPathId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $useCase = new UpdateLearningPathProgress($pathsRepo, $events, $this->mapper);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Learning path not found');
        $useCase->execute(LearningPathId::generate()->value());
    }
}
