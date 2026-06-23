<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\Services;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\Services\CareerScoreCalculator;
use Modules\CareerProfile\Domain\Services\LinkedInOptimizer;
use Modules\CareerProfile\Domain\Services\ResumeGenerator;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use PHPUnit\Framework\TestCase;

final class CareerProfileServicesTest extends TestCase
{
    public function test_career_score_calculator_returns_zero_for_empty_profile(): void
    {
        $calculator = new CareerScoreCalculator();
        $profile = CareerProfile::create(CareerProfileId::generate(), StudentId::generate(), 'التخصص');

        $score = $calculator->calculate($profile, null, 0.0);

        $this->assertIsInt($score);
        $this->assertSame(0, $score);
    }

    public function test_career_score_calculator_returns_max_score(): void
    {
        $calculator = new CareerScoreCalculator();
        $profileId = CareerProfileId::generate();
        $profile = CareerProfile::create($profileId, StudentId::generate(), 'الهندسة');

        for ($i = 0; $i < 3; $i++) {
            $profile->addPortfolioItem(
                PortfolioItemId::generate(),
                "مشروع $i",
                'وصف',
                null,
                null,
                new DateTimeImmutable('2026-01-01'),
                null,
                [],
            );
        }

        $profile->addExperience(
            ExperienceId::generate(),
            'شركة',
            'وظيفة',
            'وصف',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );

        $goal1 = CareerGoal::reconstitute(
            CareerGoalId::generate(),
            $profileId,
            'هدف 1',
            new DateTimeImmutable('2026-12-31'),
            GoalStatus::COMPLETED,
            100,
        );
        $goal2 = CareerGoal::reconstitute(
            CareerGoalId::generate(),
            $profileId,
            'هدف 2',
            new DateTimeImmutable('2026-12-31'),
            GoalStatus::COMPLETED,
            100,
        );

        $refl = new \ReflectionClass($profile);
        $goalsProp = $refl->getProperty('careerGoals');
        $goalsProp->setAccessible(true);
        $goalsProp->setValue($profile, [$goal1, $goal2]);

        $skillProfile = SkillProfile::reconstitute(
            SkillProfileId::generate(),
            StudentId::generate(),
            [
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'Laravel', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'Vue', SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'React', SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'SQL', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED),
            ],
            [
                CertificationStub::create(CertificationId::generate(), SkillProfileId::generate(), 'AWS', 'Amazon', new DateTimeImmutable('2026-01-01')),
                CertificationStub::create(CertificationId::generate(), SkillProfileId::generate(), 'Google', 'Google', new DateTimeImmutable('2026-02-01')),
            ],
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );

        $score = $calculator->calculate($profile, $skillProfile, 4.0);

        $this->assertSame(100, $score);
    }

    public function test_career_score_calculator_handles_skill_profile_null(): void
    {
        $calculator = new CareerScoreCalculator();
        $profile = CareerProfile::create(CareerProfileId::generate(), StudentId::generate(), 'الهندسة');

        $score = $calculator->calculate($profile, null, 2.0);

        $this->assertIsInt($score);
        $this->assertGreaterThanOrEqual(0, $score);
    }

    public function test_linkedin_optimizer_returns_100_with_complete_profile(): void
    {
        $optimizer = new LinkedInOptimizer();
        $profileId = CareerProfileId::generate();
        $profile = CareerProfile::create($profileId, StudentId::generate(), 'علوم الحاسب', str_repeat('a', 101));

        $profile->addExperience(
            ExperienceId::generate(),
            'شركة أ',
            'وظيفة أ',
            'وصف',
            new DateTimeImmutable('2024-01-01'),
            new DateTimeImmutable('2024-12-31'),
            false,
        );
        $profile->addExperience(
            ExperienceId::generate(),
            'شركة ب',
            'وظيفة ب',
            'وصف',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );

        $profile->addPortfolioItem(
            PortfolioItemId::generate(),
            'مشروع',
            'وصف',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            [],
        );

        $skillProfile = SkillProfile::reconstitute(
            SkillProfileId::generate(),
            StudentId::generate(),
            [
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'PHP', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'Laravel', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'Vue', SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'React', SkillCategory::PROGRAMMING, SkillLevel::INTERMEDIATE),
                Skill::create(SkillId::generate(), SkillProfileId::generate(), 'SQL', SkillCategory::PROGRAMMING, SkillLevel::ADVANCED),
            ],
            [
                CertificationStub::create(CertificationId::generate(), SkillProfileId::generate(), 'AWS', 'Amazon', new DateTimeImmutable('2026-01-01')),
            ],
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );

        $result = $optimizer->optimize($profile, $skillProfile);

        $this->assertSame(100, $result['score']);
        $this->assertEmpty($result['recommendations']);
    }

    public function test_linkedin_optimizer_gives_recommendations_for_empty_profile(): void
    {
        $optimizer = new LinkedInOptimizer();
        $profile = CareerProfile::create(CareerProfileId::generate(), StudentId::generate(), '');

        $result = $optimizer->optimize($profile, null);

        $this->assertSame(0, $result['score']);
        $this->assertNotEmpty($result['recommendations']);
    }

    public function test_linkedin_optimizer_handles_short_summary(): void
    {
        $optimizer = new LinkedInOptimizer();
        $profile = CareerProfile::create(CareerProfileId::generate(), StudentId::generate(), 'الهندسة', 'ملخص قصير');

        $result = $optimizer->optimize($profile, null);

        $this->assertSame(30, $result['score']);
        $this->assertNotEmpty($result['recommendations']);
    }

    public function test_resume_generator_generates_markdown(): void
    {
        $generator = new ResumeGenerator();

        $profileId = CareerProfileId::generate();
        $studentId = StudentId::generate();
        $profile = CareerProfile::create($profileId, $studentId, 'الهندسة', 'ملخص مهني');
        $profile->addExperience(
            ExperienceId::generate(),
            'شركة',
            'مطور',
            'وصف',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );

        $result = $generator->generate($profile, null, 'أحمد', 'ahmed@test.com');

        $this->assertStringContainsString('أحمد', $result);
        $this->assertStringContainsString('ahmed@test.com', $result);
        $this->assertStringContainsString('الهندسة', $result);
        $this->assertStringContainsString('شركة', $result);
    }

    public function test_resume_generator_handles_empty_profile(): void
    {
        $generator = new ResumeGenerator();
        $profile = CareerProfile::create(CareerProfileId::generate(), StudentId::generate(), 'غير محدد');

        $result = $generator->generate($profile, null, 'طالب', 'student@test.com');

        $this->assertStringContainsString('طالب', $result);
        $this->assertStringContainsString('student@test.com', $result);
    }
}

class CertificationStub
{
    private function __construct(
        private readonly CertificationId $id,
        private readonly SkillProfileId $skillProfileId,
        private string $name,
        private string $issuer,
        private DateTimeImmutable $issueDate,
    ) {}

    public static function create(
        CertificationId $id,
        SkillProfileId $skillProfileId,
        string $name,
        string $issuer,
        DateTimeImmutable $issueDate,
    ): self {
        return new self($id, $skillProfileId, $name, $issuer, $issueDate);
    }

    public function name(): string { return $this->name; }
    public function issuer(): string { return $this->issuer; }
    public function issueDate(): DateTimeImmutable { return $this->issueDate; }
    public function id(): CertificationId { return $this->id; }
}
