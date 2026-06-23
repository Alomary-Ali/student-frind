<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\CareerProfileDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Application\UseCases\AddExperience;
use Modules\CareerProfile\Application\UseCases\AddPortfolioItem;
use Modules\CareerProfile\Application\UseCases\CreateCareerGoal;
use Modules\CareerProfile\Application\UseCases\CreateCareerProfile;
use Modules\CareerProfile\Application\UseCases\GetCareerProfile;
use Modules\CareerProfile\Application\UseCases\UpdateCareerProfile;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Tests\TestCase;

final class NewCareerProfileUseCasesTest extends TestCase
{
    private CareerProfileMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new CareerProfileMapper;
    }

    public function test_create_career_profile_creates_and_dispatches_event(): void
    {
        $studentId = StudentId::generate();
        $studentIdValue = $studentId->value();

        $repo = new class implements CareerProfileRepositoryInterface
        {
            public ?CareerProfile $saved = null;

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->saved = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new CreateCareerProfile($repo, $events, $this->mapper);
        $dto = $useCase->execute($studentIdValue, 'الهندسة', 'ملخص شخصي');

        $this->assertInstanceOf(CareerProfileDto::class, $dto);
        $this->assertSame('الهندسة', $dto->major);
        $this->assertSame('ملخص شخصي', $dto->summary);
        $this->assertSame($studentIdValue, $dto->studentId);
        $this->assertNotNull($repo->saved);
    }

    public function test_create_career_profile_without_summary(): void
    {
        $studentId = StudentId::generate();
        $studentIdValue = $studentId->value();

        $repo = new class implements CareerProfileRepositoryInterface
        {
            public ?CareerProfile $saved = null;

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->saved = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new CreateCareerProfile($repo, $events, $this->mapper);
        $dto = $useCase->execute($studentIdValue, 'الرياضيات');

        $this->assertSame('الرياضيات', $dto->major);
        $this->assertSame('', $dto->summary);
        $this->assertNotNull($repo->saved);
    }

    public function test_get_career_profile_returns_profile(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void {}

            public function delete(CareerProfileId $id): void {}
        };

        $useCase = new GetCareerProfile($repo, $this->mapper);
        $dto = $useCase->execute($studentId->value());

        $this->assertNotNull($dto);
        $this->assertSame('الهندسة', $dto->major);
    }

    public function test_get_career_profile_returns_null_when_not_found(): void
    {
        $repo = new class implements CareerProfileRepositoryInterface
        {
            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return null;
            }

            public function save(CareerProfile $profile): void {}

            public function delete(CareerProfileId $id): void {}
        };

        $useCase = new GetCareerProfile($repo, $this->mapper);
        $dto = $useCase->execute(StudentId::generate()->value());

        $this->assertNull($dto);
    }

    public function test_update_career_profile_updates_and_saves(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );
        $profile->releaseEvents();

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;
            public ?CareerProfile $saved = null;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->saved = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $useCase = new UpdateCareerProfile($repo, $this->mapper);
        $dto = $useCase->execute($studentId->value(), [
            'major' => 'الذكاء الاصطناعي',
            'summary' => 'ملخص محدث',
            'interests' => ['ML', 'AI'],
            'languages' => ['English', 'Arabic'],
        ]);

        $this->assertSame('الذكاء الاصطناعي', $dto->major);
        $this->assertSame('ملخص محدث', $dto->summary);
        $this->assertNotNull($repo->saved);
    }

    public function test_update_career_profile_throws_when_not_found(): void
    {
        $repo = new class implements CareerProfileRepositoryInterface
        {
            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return null;
            }

            public function save(CareerProfile $profile): void {}

            public function delete(CareerProfileId $id): void {}
        };

        $useCase = new UpdateCareerProfile($repo, $this->mapper);

        $this->expectException(\RuntimeException::class);
        $useCase->execute(StudentId::generate()->value(), ['major' => 'الهندسة']);
    }

    public function test_add_experience_adds_and_dispatches_event(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );
        $profile->releaseEvents();

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;
            public ?CareerProfile $saved = null;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->saved = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new AddExperience($repo, $events, $this->mapper);
        $dto = $useCase->execute($studentId->value(), [
            'company' => 'شركة جوجل',
            'position' => 'مطور برمجيات',
            'description' => 'تطوير تطبيقات ويب',
            'start_date' => '2025-01-01',
            'is_current' => true,
        ]);

        $this->assertCount(1, $dto->experiences);
        $this->assertSame('شركة جوجل', $dto->experiences[0]->company);
        $this->assertNotNull($repo->saved);
    }

    public function test_add_experience_throws_when_profile_not_found(): void
    {
        $repo = new class implements CareerProfileRepositoryInterface
        {
            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return null;
            }

            public function save(CareerProfile $profile): void {}

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $useCase = new AddExperience($repo, $events, $this->mapper);

        $this->expectException(\RuntimeException::class);
        $useCase->execute(StudentId::generate()->value(), [
            'company' => 'شركة',
            'position' => 'وظيفة',
            'description' => 'وصف',
            'start_date' => '2025-01-01',
        ]);
    }

    public function test_add_portfolio_item_adds_and_dispatches_event(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );
        $profile->releaseEvents();

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;
            public ?CareerProfile $saved = null;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->saved = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new AddPortfolioItem($repo, $events, $this->mapper);
        $dto = $useCase->execute($studentId->value(), [
            'title' => 'نظام إدارة المدرسة',
            'description' => 'نظام متكامل لإدارة المدارس',
            'start_date' => '2026-01-01',
            'technologies' => ['Laravel', 'Vue.js'],
        ]);

        $this->assertCount(1, $dto->portfolioItems);
        $this->assertSame('نظام إدارة المدرسة', $dto->portfolioItems[0]->title);
        $this->assertNotNull($repo->saved);
    }

    public function test_add_portfolio_item_throws_when_profile_not_found(): void
    {
        $repo = new class implements CareerProfileRepositoryInterface
        {
            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return null;
            }

            public function save(CareerProfile $profile): void {}

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $useCase = new AddPortfolioItem($repo, $events, $this->mapper);

        $this->expectException(\RuntimeException::class);
        $useCase->execute(StudentId::generate()->value(), [
            'title' => 'مشروع',
            'description' => 'وصف',
            'start_date' => '2026-01-01',
        ]);
    }

    public function test_create_career_goal_creates_and_dispatches_event(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );
        $profile->releaseEvents();

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;
            public ?CareerProfile $saved = null;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->saved = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $events->expects($this->once())->method('dispatch');

        $useCase = new CreateCareerGoal($repo, $events, $this->mapper);
        $targetDate = date('Y-m-d', strtotime('+3 months'));
        $dto = $useCase->execute($studentId->value(), [
            'title' => 'الحصول على شهادة Laravel',
            'target_date' => $targetDate,
        ]);

        $this->assertCount(1, $dto->careerGoals);
        $this->assertSame('الحصول على شهادة Laravel', $dto->careerGoals[0]->title);
        $this->assertNotNull($repo->saved);
    }

    public function test_create_career_goal_throws_when_profile_not_found(): void
    {
        $repo = new class implements CareerProfileRepositoryInterface
        {
            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return null;
            }

            public function save(CareerProfile $profile): void {}

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $useCase = new CreateCareerGoal($repo, $events, $this->mapper);

        $this->expectException(\RuntimeException::class);
        $useCase->execute(StudentId::generate()->value(), [
            'title' => 'هدف',
            'target_date' => date('Y-m-d', strtotime('+1 month')),
        ]);
    }

    public function test_add_multiple_experiences_to_same_profile(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );
        $profile->releaseEvents();

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->profile = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $useCase = new AddExperience($repo, $events, $this->mapper);

        $dto1 = $useCase->execute($studentId->value(), [
            'company' => 'شركة أ',
            'position' => 'مطور',
            'description' => 'الخبرة الأولى',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]);
        $this->assertCount(1, $dto1->experiences);

        $dto2 = $useCase->execute($studentId->value(), [
            'company' => 'شركة ب',
            'position' => 'مطور أول',
            'description' => 'الخبرة الثانية',
            'start_date' => '2025-01-01',
            'is_current' => true,
        ]);
        $this->assertCount(2, $dto2->experiences);
        $this->assertSame('شركة ب', $dto2->experiences[1]->company);
    }

    public function test_add_multiple_portfolio_items(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );
        $profile->releaseEvents();

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->profile = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $useCase = new AddPortfolioItem($repo, $events, $this->mapper);

        $dto1 = $useCase->execute($studentId->value(), [
            'title' => 'مشروع أ',
            'description' => 'وصف المشروع الأول',
            'start_date' => '2026-01-01',
        ]);
        $this->assertCount(1, $dto1->portfolioItems);

        $dto2 = $useCase->execute($studentId->value(), [
            'title' => 'مشروع ب',
            'description' => 'وصف المشروع الثاني',
            'start_date' => '2026-02-01',
            'technologies' => ['React', 'Node.js'],
        ]);
        $this->assertCount(2, $dto2->portfolioItems);
        $this->assertSame('مشروع ب', $dto2->portfolioItems[1]->title);
        $this->assertSame(['React', 'Node.js'], $dto2->portfolioItems[1]->technologies);
    }

    public function test_add_multiple_career_goals(): void
    {
        $studentId = StudentId::generate();
        $profile = CareerProfile::create(
            CareerProfileId::generate(), $studentId, 'الهندسة',
        );
        $profile->releaseEvents();

        $repo = new class($studentId, $profile) implements CareerProfileRepositoryInterface
        {
            private StudentId $sid;
            private CareerProfile $profile;

            public function __construct(StudentId $sid, CareerProfile $profile)
            {
                $this->sid = $sid;
                $this->profile = $profile;
            }

            public function findById(CareerProfileId $id): ?CareerProfile
            {
                return null;
            }

            public function findByStudentId(StudentId $studentId): ?CareerProfile
            {
                return $studentId->equals($this->sid) ? $this->profile : null;
            }

            public function save(CareerProfile $profile): void
            {
                $this->profile = $profile;
            }

            public function delete(CareerProfileId $id): void {}
        };

        $events = $this->createMock(EventDispatcherInterface::class);
        $useCase = new CreateCareerGoal($repo, $events, $this->mapper);

        $future = date('Y-m-d', strtotime('+3 months'));

        $dto1 = $useCase->execute($studentId->value(), [
            'title' => 'الهدف الأول',
            'target_date' => $future,
        ]);
        $this->assertCount(1, $dto1->careerGoals);

        $dto2 = $useCase->execute($studentId->value(), [
            'title' => 'الهدف الثاني',
            'target_date' => $future,
        ]);
        $this->assertCount(2, $dto2->careerGoals);
        $this->assertSame('الهدف الثاني', $dto2->careerGoals[1]->title);
    }
}
