<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Feature\Infrastructure\Persistence;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\CareerProfile\Domain\Entities\CareerGoal;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\Entities\Experience;
use Modules\CareerProfile\Domain\Entities\PortfolioItem;
use Modules\CareerProfile\Domain\Entities\Resume;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use Modules\CareerProfile\Infrastructure\Persistence\EloquentCareerGoalRepository;
use Modules\CareerProfile\Infrastructure\Persistence\EloquentCareerProfileRepository;
use Modules\CareerProfile\Infrastructure\Persistence\EloquentExperienceRepository;
use Modules\CareerProfile\Infrastructure\Persistence\EloquentPortfolioItemRepository;
use Modules\CareerProfile\Infrastructure\Persistence\EloquentResumeRepository;
use Tests\TestCase;

final class CareerProfileRepositoriesTest extends TestCase
{
    use RefreshDatabase;

    private EloquentCareerProfileRepository $profileRepo;
    private EloquentCareerGoalRepository $goalRepo;
    private EloquentExperienceRepository $experienceRepo;
    private EloquentPortfolioItemRepository $portfolioRepo;
    private EloquentResumeRepository $resumeRepo;

    private CareerProfile $profile;
    private string $profileId;
    private string $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileRepo = new EloquentCareerProfileRepository;
        $this->goalRepo = new EloquentCareerGoalRepository;
        $this->experienceRepo = new EloquentExperienceRepository;
        $this->portfolioRepo = new EloquentPortfolioItemRepository;
        $this->resumeRepo = new EloquentResumeRepository;

        $user = User::factory()->create(['role' => 'student']);
        $student = EloquentStudent::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'user_id' => $user->id,
            'student_number' => 'STU-REPO',
            'academic_status' => 'enrolled',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.0,
        ]);

        $this->studentId = $student->id;
        $this->profile = CareerProfile::create(
            CareerProfileId::generate(),
            StudentId::of($this->studentId),
            'الهندسة',
            'ملخص اختبار',
        );
        $this->profileId = $this->profile->id()->value();
    }

    public function test_career_profile_repository_save_and_find_by_id(): void
    {
        $this->profileRepo->save($this->profile);

        $found = $this->profileRepo->findById($this->profile->id());

        $this->assertNotNull($found);
        $this->assertSame($this->profileId, $found->id()->value());
        $this->assertSame('الهندسة', $found->major());
    }

    public function test_career_profile_repository_find_by_student_id(): void
    {
        $this->profileRepo->save($this->profile);

        $found = $this->profileRepo->findByStudentId(StudentId::of($this->studentId));

        $this->assertNotNull($found);
        $this->assertSame($this->profileId, $found->id()->value());
    }

    public function test_career_profile_repository_find_by_id_returns_null(): void
    {
        $found = $this->profileRepo->findById(CareerProfileId::generate());
        $this->assertNull($found);
    }

    public function test_career_profile_repository_update(): void
    {
        $this->profileRepo->save($this->profile);

        $this->profile->updateProfile('الذكاء الاصطناعي', 'ملخص محدث', [], []);
        $this->profileRepo->save($this->profile);

        $found = $this->profileRepo->findById($this->profile->id());

        $this->assertSame('الذكاء الاصطناعي', $found->major());
        $this->assertSame('ملخص محدث', $found->summary());
    }

    public function test_career_profile_repository_delete(): void
    {
        $this->profileRepo->save($this->profile);
        $this->profileRepo->delete($this->profile->id());

        $found = $this->profileRepo->findById($this->profile->id());
        $this->assertNull($found);
    }

    public function test_career_goal_repository_save_and_find(): void
    {
        $this->profileRepo->save($this->profile);

        $goal = CareerGoal::create(
            CareerGoalId::generate(),
            $this->profile->id(),
            'تعلم Laravel',
            new DateTimeImmutable('2026-12-31'),
        );
        $this->goalRepo->save($goal);

        $found = $this->goalRepo->findById($goal->id());

        $this->assertNotNull($found);
        $this->assertSame('تعلم Laravel', $found->title());
        $this->assertSame(GoalStatus::NOT_STARTED, $found->status());
    }

    public function test_career_goal_repository_update(): void
    {
        $this->profileRepo->save($this->profile);

        $goal = CareerGoal::create(
            CareerGoalId::generate(),
            $this->profile->id(),
            'هدف قديم',
            new DateTimeImmutable('2026-12-31'),
        );
        $this->goalRepo->save($goal);

        $goal->updateProgress(75);
        $this->goalRepo->save($goal);

        $found = $this->goalRepo->findById($goal->id());

        $this->assertSame(75, $found->progress());
        $this->assertSame(GoalStatus::IN_PROGRESS, $found->status());
    }

    public function test_career_goal_repository_delete(): void
    {
        $this->profileRepo->save($this->profile);

        $goal = CareerGoal::create(
            CareerGoalId::generate(),
            $this->profile->id(),
            'هدف',
            new DateTimeImmutable('2026-12-31'),
        );
        $this->goalRepo->save($goal);
        $this->goalRepo->delete($goal->id());

        $found = $this->goalRepo->findById($goal->id());
        $this->assertNull($found);
    }

    public function test_experience_repository_save_and_find(): void
    {
        $this->profileRepo->save($this->profile);

        $exp = Experience::create(
            ExperienceId::generate(),
            $this->profile->id(),
            'شركة جوجل',
            'مطور',
            'وصف الخبرة',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );
        $this->experienceRepo->save($exp);

        $found = $this->experienceRepo->findById($exp->id());

        $this->assertNotNull($found);
        $this->assertSame('شركة جوجل', $found->company());
        $this->assertSame('مطور', $found->position());
        $this->assertTrue($found->isCurrent());
    }

    public function test_experience_repository_update(): void
    {
        $this->profileRepo->save($this->profile);

        $exp = Experience::create(
            ExperienceId::generate(),
            $this->profile->id(),
            'شركة قديمة',
            'مطور مبتدئ',
            'وصف',
            new DateTimeImmutable('2024-01-01'),
            new DateTimeImmutable('2024-12-31'),
            false,
        );
        $this->experienceRepo->save($exp);

        $exp->update('شركة جديدة', 'مطور أول', 'وصف جديد',
            new DateTimeImmutable('2025-06-01'), null, true);
        $this->experienceRepo->save($exp);

        $found = $this->experienceRepo->findById($exp->id());

        $this->assertSame('شركة جديدة', $found->company());
        $this->assertTrue($found->isCurrent());
        $this->assertNull($found->endDate());
    }

    public function test_experience_repository_delete(): void
    {
        $this->profileRepo->save($this->profile);

        $exp = Experience::create(
            ExperienceId::generate(),
            $this->profile->id(),
            'شركة',
            'وظيفة',
            'وصف',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );
        $this->experienceRepo->save($exp);
        $this->experienceRepo->delete($exp->id());

        $found = $this->experienceRepo->findById($exp->id());
        $this->assertNull($found);
    }

    public function test_portfolio_item_repository_save_and_find(): void
    {
        $this->profileRepo->save($this->profile);

        $item = PortfolioItem::create(
            PortfolioItemId::generate(),
            $this->profile->id(),
            'مشروع رفيق الطالب',
            'منصة متكاملة',
            'https://rafiq.test',
            null,
            new DateTimeImmutable('2026-01-01'),
            new DateTimeImmutable('2026-06-01'),
            ['Laravel', 'Vue'],
        );
        $this->portfolioRepo->save($item);

        $found = $this->portfolioRepo->findById($item->id());

        $this->assertNotNull($found);
        $this->assertSame('مشروع رفيق الطالب', $found->title());
        $this->assertSame(['Laravel', 'Vue'], $found->technologies());
    }

    public function test_portfolio_item_repository_update(): void
    {
        $this->profileRepo->save($this->profile);

        $item = PortfolioItem::create(
            PortfolioItemId::generate(),
            $this->profile->id(),
            'عنوان قديم',
            'وصف قديم',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            [],
        );
        $this->portfolioRepo->save($item);

        $item->update('عنوان جديد', 'وصف جديد', 'https://new.com', null,
            new DateTimeImmutable('2026-03-01'), null, ['React']);
        $this->portfolioRepo->save($item);

        $found = $this->portfolioRepo->findById($item->id());

        $this->assertSame('عنوان جديد', $found->title());
        $this->assertSame('https://new.com', $found->projectUrl());
    }

    public function test_portfolio_item_repository_delete(): void
    {
        $this->profileRepo->save($this->profile);

        $item = PortfolioItem::create(
            PortfolioItemId::generate(),
            $this->profile->id(),
            'مشروع',
            'وصف',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            [],
        );
        $this->portfolioRepo->save($item);
        $this->portfolioRepo->delete($item->id());

        $found = $this->portfolioRepo->findById($item->id());
        $this->assertNull($found);
    }

    public function test_resume_repository_save_and_find(): void
    {
        $this->profileRepo->save($this->profile);

        $resume = Resume::create(
            ResumeId::generate(),
            $this->profile->id(),
            ResumeTemplate::MODERN,
            'محتوى السيرة الذاتية',
        );
        $this->resumeRepo->save($resume);

        $found = $this->resumeRepo->findById($resume->id());

        $this->assertNotNull($found);
        $this->assertSame(ResumeTemplate::MODERN, $found->template());
        $this->assertSame('محتوى السيرة الذاتية', $found->content());
    }

    public function test_resume_repository_update(): void
    {
        $this->profileRepo->save($this->profile);

        $resume = Resume::create(
            ResumeId::generate(),
            $this->profile->id(),
            ResumeTemplate::ATS_FRIENDLY,
            'محتوى قديم',
        );
        $this->resumeRepo->save($resume);

        $resume->updateContent('محتوى جديد');
        $resume->changeTemplate(ResumeTemplate::PROFESSIONAL);
        $this->resumeRepo->save($resume);

        $found = $this->resumeRepo->findById($resume->id());

        $this->assertSame('محتوى جديد', $found->content());
        $this->assertSame(ResumeTemplate::PROFESSIONAL, $found->template());
    }

    public function test_resume_repository_delete(): void
    {
        $this->profileRepo->save($this->profile);

        $resume = Resume::create(
            ResumeId::generate(),
            $this->profile->id(),
            ResumeTemplate::MODERN,
            'محتوى',
        );
        $this->resumeRepo->save($resume);
        $this->resumeRepo->delete($resume->id());

        $found = $this->resumeRepo->findById($resume->id());
        $this->assertNull($found);
    }

    public function test_career_profile_repository_saves_with_relations(): void
    {
        $this->profile->addExperience(
            ExperienceId::generate(),
            'شركة',
            'وظيفة',
            'وصف',
            new DateTimeImmutable('2025-01-01'),
            null,
            true,
        );

        $this->profile->addPortfolioItem(
            PortfolioItemId::generate(),
            'مشروع',
            'وصف',
            null,
            null,
            new DateTimeImmutable('2026-01-01'),
            null,
            ['PHP'],
        );

        $this->profileRepo->save($this->profile);

        $found = $this->profileRepo->findById($this->profile->id());

        $this->assertCount(1, $found->experiences());
        $this->assertCount(1, $found->portfolioItems());
    }
}
