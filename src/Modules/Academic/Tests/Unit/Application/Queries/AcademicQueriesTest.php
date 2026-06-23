<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Application\Queries;

use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Application\Queries\GetCurriculumCourses;
use Modules\Academic\Application\Queries\GetGraduationProgress;
use Modules\Academic\Application\Queries\GetStudentAcademicProfile;
use Modules\Academic\Application\Queries\ListCourses;
use Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface;
use Modules\Academic\Domain\Contracts\AcademicPlanRepositoryInterface;
use Modules\Academic\Domain\Contracts\CourseRepositoryInterface;
use Modules\Academic\Domain\Contracts\CurriculumRepositoryInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\ReadModels\GraduationProgress;
use Modules\Academic\Domain\ReadModels\StudentAcademicProfile;
use PHPUnit\Framework\TestCase;

final class AcademicQueriesTest extends TestCase
{
    public function test_get_graduation_progress_returns_structured_data(): void
    {
        $reader = $this->createMock(AcademicPlanReaderInterface::class);
        $query = new GetGraduationProgress($reader);

        $reader->expects($this->once())
            ->method('getGraduationProgress')
            ->with('student-uuid')
            ->willReturn(new GraduationProgress(
                studentId: 'student-uuid',
                creditsEarned: 60,
                creditsRequired: 120,
                completionPercentage: 50.0,
                isOnTrack: true,
                cumulativeGpa: 3.2,
                estimatedGraduationDate: '2029-06-15',
            ));

        $result = $query->execute('student-uuid');

        $this->assertIsArray($result);
        $this->assertSame('student-uuid', $result['student_id']);
        $this->assertSame(60, $result['credits_earned']);
        $this->assertSame(120, $result['credits_required']);
        $this->assertSame(50.0, $result['completion_percentage']);
        $this->assertTrue($result['is_on_track']);
        $this->assertSame(3.2, $result['cumulative_gpa']);
        $this->assertSame('2029-06-15', $result['estimated_graduation_date']);
    }

    public function test_get_graduation_progress_returns_null_when_no_progress(): void
    {
        $reader = $this->createMock(AcademicPlanReaderInterface::class);
        $query = new GetGraduationProgress($reader);

        $reader->expects($this->once())
            ->method('getGraduationProgress')
            ->willReturn(null);

        $result = $query->execute('student-uuid');

        $this->assertNull($result);
    }

    public function test_get_student_academic_profile_returns_structured_data(): void
    {
        $reader = $this->createMock(AcademicPlanReaderInterface::class);
        $query = new GetStudentAcademicProfile($reader);

        $reader->expects($this->once())
            ->method('getStudentProfile')
            ->with('student-uuid')
            ->willReturn(new StudentAcademicProfile(
                studentId: 'student-uuid',
                userId: 'user-uuid',
                studentNumber: '2024001',
                academicStatus: 'active',
                academicStanding: 'good_standing',
                cumulativeGpa: 3.5,
                institutionId: 'inst-uuid',
                createdAt: '2026-01-15T00:00:00+00:00',
            ));

        $result = $query->execute('student-uuid');

        $this->assertIsArray($result);
        $this->assertSame('student-uuid', $result['id']);
        $this->assertSame('user-uuid', $result['user_id']);
        $this->assertSame('2024001', $result['student_number']);
        $this->assertSame('active', $result['academic_status']);
        $this->assertSame('good_standing', $result['academic_standing']);
        $this->assertSame(3.5, $result['cumulative_gpa']);
        $this->assertSame('inst-uuid', $result['institution_id']);
        $this->assertSame('2026-01-15T00:00:00+00:00', $result['created_at']);
    }

    public function test_get_student_academic_profile_returns_null_when_not_found(): void
    {
        $reader = $this->createMock(AcademicPlanReaderInterface::class);
        $query = new GetStudentAcademicProfile($reader);

        $reader->expects($this->once())
            ->method('getStudentProfile')
            ->willReturn(null);

        $result = $query->execute('student-uuid');

        $this->assertNull($result);
    }

    public function test_list_courses_returns_paginated_result(): void
    {
        $courseRepository = $this->createMock(CourseRepositoryInterface::class);
        $mapper = new AcademicMapper();
        $query = new ListCourses($courseRepository, $mapper);

        $paginatedResult = new class implements \IteratorAggregate {
            public function items(): array { return []; }
            public function currentPage(): int { return 1; }
            public function perPage(): int { return 15; }
            public function total(): int { return 0; }
            public function lastPage(): int { return 1; }
            public function firstItem(): ?int { return null; }
            public function lastItem(): ?int { return null; }
            public function getIterator(): \Traversable { return new \ArrayIterator([]); }
        };

        $courseRepository->expects($this->once())
            ->method('findAllActivePaginated')
            ->with(1, 15)
            ->willReturn($paginatedResult);

        $result = $query->execute();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertSame(1, $result['pagination']['current_page']);
        $this->assertSame(15, $result['pagination']['per_page']);
        $this->assertSame(0, $result['pagination']['total']);
    }

    public function test_list_courses_with_custom_pagination(): void
    {
        $courseRepository = $this->createMock(CourseRepositoryInterface::class);
        $mapper = new AcademicMapper();
        $query = new ListCourses($courseRepository, $mapper);

        $paginatedResult = new class implements \IteratorAggregate {
            public function items(): array { return []; }
            public function currentPage(): int { return 2; }
            public function perPage(): int { return 10; }
            public function total(): int { return 25; }
            public function lastPage(): int { return 3; }
            public function firstItem(): ?int { return 11; }
            public function lastItem(): ?int { return 20; }
            public function getIterator(): \Traversable { return new \ArrayIterator([]); }
        };

        $courseRepository->expects($this->once())
            ->method('findAllActivePaginated')
            ->with(2, 10)
            ->willReturn($paginatedResult);

        $result = $query->execute(page: 2, perPage: 10);

        $this->assertSame(2, $result['pagination']['current_page']);
        $this->assertSame(10, $result['pagination']['per_page']);
        $this->assertSame(25, $result['pagination']['total']);
        $this->assertSame(3, $result['pagination']['last_page']);
        $this->assertSame(11, $result['pagination']['from']);
        $this->assertSame(20, $result['pagination']['to']);
    }
}
