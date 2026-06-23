<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
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

final class CareerProfileUseCasesTest extends TestCase
{
    private CareerProfileRepositoryInterface $profiles;
    private EventDispatcherInterface $events;
    private CareerProfileMapper $mapper;
    private string $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profiles = $this->createMock(CareerProfileRepositoryInterface::class);
        $this->events = $this->createMock(EventDispatcherInterface::class);
        $this->mapper = new CareerProfileMapper;
        $this->studentId = StudentId::generate()->value();
    }

    public function test_create_career_profile_execute(): void
    {
        $this->profiles->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(CareerProfile::class));

        $this->events->expects($this->once())
            ->method('dispatch')
            ->with($this->isType('array'));

        $useCase = new CreateCareerProfile($this->profiles, $this->events, $this->mapper);
        $dto = $useCase->execute($this->studentId, 'الهندسة', 'ملخص');

        $this->assertSame('الهندسة', $dto->major);
        $this->assertSame('ملخص', $dto->summary);
        $this->assertSame($this->studentId, $dto->studentId);
    }

    public function test_create_career_profile_without_summary(): void
    {
        $this->profiles->expects($this->once())->method('save');
        $this->events->expects($this->once())->method('dispatch');

        $useCase = new CreateCareerProfile($this->profiles, $this->events, $this->mapper);
        $dto = $useCase->execute($this->studentId, 'الرياضيات');

        $this->assertSame('الرياضيات', $dto->major);
        $this->assertSame('', $dto->summary);
    }

    public function test_get_career_profile_returns_profile(): void
    {
        $studentVo = StudentId::of($this->studentId);
        $profile = CareerProfile::create(CareerProfileId::generate(), $studentVo, 'الهندسة');

        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn($profile);

        $useCase = new GetCareerProfile($this->profiles, $this->mapper);
        $dto = $useCase->execute($this->studentId);

        $this->assertNotNull($dto);
        $this->assertSame('الهندسة', $dto->major);
    }

    public function test_get_career_profile_returns_null_when_not_found(): void
    {
        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn(null);

        $useCase = new GetCareerProfile($this->profiles, $this->mapper);
        $dto = $useCase->execute($this->studentId);

        $this->assertNull($dto);
    }

    public function test_update_career_profile_execute(): void
    {
        $studentVo = StudentId::of($this->studentId);
        $profile = CareerProfile::create(CareerProfileId::generate(), $studentVo, 'الهندسة');
        $profile->releaseEvents();

        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn($profile);

        $this->profiles->expects($this->once())
            ->method('save')
            ->with($profile);

        $useCase = new UpdateCareerProfile($this->profiles, $this->mapper);
        $dto = $useCase->execute($this->studentId, [
            'major' => 'الذكاء الاصطناعي',
            'summary' => 'ملخص محدث',
            'interests' => ['ML'],
            'languages' => ['English'],
        ]);

        $this->assertSame('الذكاء الاصطناعي', $dto->major);
        $this->assertSame('ملخص محدث', $dto->summary);
    }

    public function test_update_career_profile_throws_when_not_found(): void
    {
        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $useCase = new UpdateCareerProfile($this->profiles, $this->mapper);
        $useCase->execute($this->studentId, ['major' => 'الهندسة']);
    }

    public function test_add_experience_execute(): void
    {
        $studentVo = StudentId::of($this->studentId);
        $profile = CareerProfile::create(CareerProfileId::generate(), $studentVo, 'الهندسة');
        $profile->releaseEvents();

        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn($profile);

        $this->profiles->expects($this->once())->method('save');
        $this->events->expects($this->once())->method('dispatch');

        $useCase = new AddExperience($this->profiles, $this->events, $this->mapper);
        $dto = $useCase->execute($this->studentId, [
            'company' => 'شركة جوجل',
            'position' => 'مطور',
            'description' => 'وصف الخبرة',
            'start_date' => '2025-01-01',
            'is_current' => true,
        ]);

        $this->assertCount(1, $dto->experiences);
        $this->assertSame('شركة جوجل', $dto->experiences[0]->company);
    }

    public function test_add_experience_throws_when_profile_not_found(): void
    {
        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $useCase = new AddExperience($this->profiles, $this->events, $this->mapper);
        $useCase->execute($this->studentId, [
            'company' => 'شركة',
            'position' => 'وظيفة',
            'description' => 'وصف',
            'start_date' => '2025-01-01',
        ]);
    }

    public function test_add_portfolio_item_execute(): void
    {
        $studentVo = StudentId::of($this->studentId);
        $profile = CareerProfile::create(CareerProfileId::generate(), $studentVo, 'الهندسة');
        $profile->releaseEvents();

        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn($profile);

        $this->profiles->expects($this->once())->method('save');
        $this->events->expects($this->once())->method('dispatch');

        $useCase = new AddPortfolioItem($this->profiles, $this->events, $this->mapper);
        $dto = $useCase->execute($this->studentId, [
            'title' => 'مشروع',
            'description' => 'وصف',
            'start_date' => '2026-01-01',
            'technologies' => ['Laravel'],
        ]);

        $this->assertCount(1, $dto->portfolioItems);
        $this->assertSame('مشروع', $dto->portfolioItems[0]->title);
    }

    public function test_add_portfolio_item_throws_when_profile_not_found(): void
    {
        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $useCase = new AddPortfolioItem($this->profiles, $this->events, $this->mapper);
        $useCase->execute($this->studentId, [
            'title' => 'مشروع',
            'description' => 'وصف',
            'start_date' => '2026-01-01',
        ]);
    }

    public function test_create_career_goal_execute(): void
    {
        $studentVo = StudentId::of($this->studentId);
        $profile = CareerProfile::create(CareerProfileId::generate(), $studentVo, 'الهندسة');
        $profile->releaseEvents();

        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn($profile);

        $this->profiles->expects($this->once())->method('save');
        $this->events->expects($this->once())->method('dispatch');

        $useCase = new CreateCareerGoal($this->profiles, $this->events, $this->mapper);
        $targetDate = date('Y-m-d', strtotime('+3 months'));
        $dto = $useCase->execute($this->studentId, [
            'title' => 'هدف جديد',
            'target_date' => $targetDate,
        ]);

        $this->assertCount(1, $dto->careerGoals);
        $this->assertSame('هدف جديد', $dto->careerGoals[0]->title);
    }

    public function test_create_career_goal_throws_when_profile_not_found(): void
    {
        $this->profiles->expects($this->once())
            ->method('findByStudentId')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $useCase = new CreateCareerGoal($this->profiles, $this->events, $this->mapper);
        $useCase->execute($this->studentId, [
            'title' => 'هدف',
            'target_date' => date('Y-m-d', strtotime('+1 month')),
        ]);
    }
}
