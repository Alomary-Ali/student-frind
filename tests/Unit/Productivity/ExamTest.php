<?php

declare(strict_types=1);

namespace Tests\Unit\Productivity;

use Modules\Productivity\Domain\Entities\Exam;
use Modules\Productivity\Domain\Enums\ExamType;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class ExamTest extends TestCase
{
    public function test_can_create_exam(): void
    {
        $exam = Exam::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: ExamType::MIDTERM,
            examDate: new \DateTimeImmutable('+14 days'),
            location: 'قاعة A',
        );

        $this->assertInstanceOf(Exam::class, $exam);
        $this->assertEquals('اختبار منتصف الفصل', $exam->title());
        $this->assertEquals('قاعة A', $exam->location());
    }

    public function test_can_mark_exam_as_completed(): void
    {
        $exam = Exam::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: ExamType::MIDTERM,
            examDate: new \DateTimeImmutable('+14 days'),
            location: 'قاعة A',
        );

        $exam->markAsCompleted();

        $this->assertEquals('completed', $exam->status());
    }

    public function test_can_mark_exam_as_cancelled(): void
    {
        $exam = Exam::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: ExamType::MIDTERM,
            examDate: new \DateTimeImmutable('+14 days'),
            location: 'قاعة A',
        );

        $exam->markAsCancelled();

        $this->assertEquals('cancelled', $exam->status());
    }

    public function test_exam_is_upcoming(): void
    {
        $exam = Exam::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: ExamType::MIDTERM,
            examDate: new \DateTimeImmutable('+14 days'),
            location: 'قاعة A',
        );

        $this->assertTrue($exam->isUpcoming());
    }

    public function test_can_convert_to_array(): void
    {
        $exam = Exam::create(
            userId: UserId::generate(),
            courseId: 'CS101',
            title: 'اختبار منتصف الفصل',
            examType: ExamType::MIDTERM,
            examDate: new \DateTimeImmutable('+14 days'),
            location: 'قاعة A',
        );

        $array = $exam->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('exam_type', $array);
    }
}
