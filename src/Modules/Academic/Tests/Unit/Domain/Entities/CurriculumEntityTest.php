<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\Curriculum;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;
use PHPUnit\Framework\TestCase;

final class CurriculumEntityTest extends TestCase
{
    public function test_curriculum_can_be_created(): void
    {
        $curriculum = Curriculum::create(
            CurriculumId::generate(),
            'Computer Science',
            'CS-2026',
            'Bachelor of Computer Science',
            Credits::of(20),
        );

        $this->assertSame('Computer Science', $curriculum->name());
        $this->assertSame('CS-2026', $curriculum->code());
        $this->assertSame('Bachelor of Computer Science', $curriculum->description());
        $this->assertSame(20, $curriculum->totalCreditsRequired()->value());
        $this->assertCount(0, $curriculum->courses());
    }

    public function test_curriculum_can_be_reconstituted(): void
    {
        $curriculum = Curriculum::reconstitute(
            CurriculumId::generate(),
            'Computer Science',
            'CS-2026',
            'Bachelor of Computer Science',
            Credits::of(20),
            'inst-1',
            new DateTimeImmutable('2026-01-01'),
        );

        $this->assertSame('Computer Science', $curriculum->name());
        $this->assertSame(20, $curriculum->totalCreditsRequired()->value());
        $this->assertCount(0, $curriculum->courses());
    }

    public function test_reconstitute_with_courses_restores_them(): void
    {
        $courseId = CourseId::generate();
        $courseId2 = CourseId::generate();

        $curriculum = Curriculum::reconstitute(
            CurriculumId::generate(),
            'CS',
            'CS-2026',
            'Desc',
            Credits::of(20),
            null,
            new DateTimeImmutable('2026-01-01'),
            [
                new \Modules\Academic\Domain\Entities\CurriculumCourse($courseId, true, 1),
                new \Modules\Academic\Domain\Entities\CurriculumCourse($courseId2, false, 2),
            ],
        );

        $this->assertCount(2, $curriculum->courses());
        $this->assertTrue($courseId->equals($curriculum->courses()[0]->courseId()));
        $this->assertTrue($curriculum->courses()[0]->isRequired());
        $this->assertSame(1, $curriculum->courses()[0]->semesterOrder());
        $this->assertFalse($curriculum->courses()[1]->isRequired());
        $this->assertSame(2, $curriculum->courses()[1]->semesterOrder());
    }

    public function test_courses_can_be_added_to_curriculum(): void
    {
        $curriculum = Curriculum::create(
            CurriculumId::generate(),
            'CS',
            'CS-2026',
            'Desc',
            Credits::of(20),
        );

        $courseId = CourseId::generate();
        $curriculum->addCourse($courseId, true, 1);

        $courses = $curriculum->courses();
        $this->assertCount(1, $courses);
        $this->assertTrue($courseId->equals($courses[0]->courseId()));
        $this->assertTrue($courses[0]->isRequired());
        $this->assertSame(1, $courses[0]->semesterOrder());
    }

    public function test_curriculum_getters_return_correct_values(): void
    {
        $id = CurriculumId::generate();
        $createdAt = new DateTimeImmutable('2026-01-01');

        $curriculum = Curriculum::reconstitute(
            $id,
            'Mathematics',
            'MATH-2026',
            'Bachelor of Mathematics',
            Credits::of(20),
            'inst-1',
            $createdAt,
        );

        $this->assertTrue($id->equals($curriculum->id()));
        $this->assertSame('Mathematics', $curriculum->name());
        $this->assertSame('MATH-2026', $curriculum->code());
        $this->assertSame('Bachelor of Mathematics', $curriculum->description());
        $this->assertSame(20, $curriculum->totalCreditsRequired()->value());
        $this->assertSame('inst-1', $curriculum->institutionId());
        $this->assertSame($createdAt, $curriculum->createdAt());
    }
}
