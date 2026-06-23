<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\Course;
use Modules\Academic\Domain\Events\CourseCreated;
use Modules\Academic\Domain\Exceptions\CourseNotActiveException;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use PHPUnit\Framework\TestCase;

final class CourseEntityTest extends TestCase
{
    public function test_course_can_be_created_with_valid_data(): void
    {
        $course = Course::create(
            CourseId::generate(),
            'CS101',
            'Introduction to Programming',
            'Fundamentals of programming',
            Credits::of(3),
        );

        $this->assertSame('CS101', $course->code());
        $this->assertSame('Introduction to Programming', $course->title());
        $this->assertSame(3, $course->creditHours()->value());
        $this->assertTrue($course->isActive());

        $events = $course->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CourseCreated::class, $events[0]);
    }

    public function test_course_can_be_reconstituted(): void
    {
        $course = Course::reconstitute(
            CourseId::generate(),
            'CS101',
            'Introduction to Programming',
            'Fundamentals',
            Credits::of(3),
            false,
            null,
            new DateTimeImmutable('2026-01-01'),
        );

        $this->assertSame('CS101', $course->code());
        $this->assertSame(3, $course->creditHours()->value());
        $this->assertFalse($course->isActive());
        $this->assertCount(0, $course->releaseEvents());
    }

    public function test_ensure_active_throws_when_course_not_active(): void
    {
        $course = Course::reconstitute(
            CourseId::generate(),
            'CS101',
            'Test',
            'Desc',
            Credits::of(3),
            false,
            null,
            new DateTimeImmutable,
        );

        $this->expectException(CourseNotActiveException::class);
        $course->ensureActive();
    }

    public function test_ensure_active_does_not_throw_when_active(): void
    {
        $course = Course::create(
            CourseId::generate(),
            'CS101',
            'Test',
            'Desc',
            Credits::of(3),
        );

        $course->ensureActive();
        $this->assertTrue(true);
    }

    public function test_deactivate_marks_course_as_inactive(): void
    {
        $course = Course::create(
            CourseId::generate(),
            'CS101',
            'Test',
            'Desc',
            Credits::of(3),
        );
        $course->releaseEvents();

        $course->deactivate();

        $this->assertFalse($course->isActive());
    }

    public function test_course_getters_return_correct_values(): void
    {
        $id = CourseId::generate();
        $institutionId = 'inst-1';
        $course = Course::create(
            $id,
            'CS101',
            'Test Title',
            'Test Description',
            Credits::of(4),
            $institutionId,
        );

        $this->assertTrue($id->equals($course->id()));
        $this->assertSame('CS101', $course->code());
        $this->assertSame('Test Title', $course->title());
        $this->assertSame('Test Description', $course->description());
        $this->assertSame(4, $course->creditHours()->value());
        $this->assertTrue($course->isActive());
        $this->assertSame($institutionId, $course->institutionId());
        $this->assertInstanceOf(DateTimeImmutable::class, $course->createdAt());
    }
}
