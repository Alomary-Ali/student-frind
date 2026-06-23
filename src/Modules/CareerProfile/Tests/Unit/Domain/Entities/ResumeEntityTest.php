<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Entities\Resume;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use PHPUnit\Framework\TestCase;

final class ResumeEntityTest extends TestCase
{
    private ResumeId $resumeId;
    private CareerProfileId $profileId;

    protected function setUp(): void
    {
        $this->resumeId = ResumeId::generate();
        $this->profileId = CareerProfileId::generate();
    }

    public function test_can_create_resume(): void
    {
        $resume = Resume::create(
            $this->resumeId,
            $this->profileId,
            ResumeTemplate::MODERN,
            'محتوى السيرة الذاتية',
        );

        $this->assertSame($this->resumeId, $resume->id());
        $this->assertSame($this->profileId, $resume->careerProfileId());
        $this->assertSame(ResumeTemplate::MODERN, $resume->template());
        $this->assertSame('محتوى السيرة الذاتية', $resume->content());
        $this->assertInstanceOf(DateTimeImmutable::class, $resume->generatedAt());
    }

    public function test_can_create_resume_with_ats_template(): void
    {
        $resume = Resume::create(
            $this->resumeId,
            $this->profileId,
            ResumeTemplate::ATS_FRIENDLY,
            'محتوى ATS',
        );

        $this->assertSame(ResumeTemplate::ATS_FRIENDLY, $resume->template());
    }

    public function test_can_create_resume_with_academic_template(): void
    {
        $resume = Resume::create(
            $this->resumeId,
            $this->profileId,
            ResumeTemplate::ACADEMIC,
            'محتوى أكاديمي',
        );

        $this->assertSame(ResumeTemplate::ACADEMIC, $resume->template());
    }

    public function test_can_create_resume_with_professional_template(): void
    {
        $resume = Resume::create(
            $this->resumeId,
            $this->profileId,
            ResumeTemplate::PROFESSIONAL,
            'محتوى احترافي',
        );

        $this->assertSame(ResumeTemplate::PROFESSIONAL, $resume->template());
    }

    public function test_can_update_content(): void
    {
        $resume = Resume::create(
            $this->resumeId,
            $this->profileId,
            ResumeTemplate::MODERN,
            'محتوى قديم',
        );

        $resume->updateContent('محتوى جديد');

        $this->assertSame('محتوى جديد', $resume->content());
    }

    public function test_can_change_template(): void
    {
        $resume = Resume::create(
            $this->resumeId,
            $this->profileId,
            ResumeTemplate::MODERN,
            'محتوى',
        );

        $resume->changeTemplate(ResumeTemplate::ACADEMIC);

        $this->assertSame(ResumeTemplate::ACADEMIC, $resume->template());
    }

    public function test_can_reconstitute_resume(): void
    {
        $generatedAt = new DateTimeImmutable('2026-01-15 10:00:00');
        $resume = Resume::reconstitute(
            $this->resumeId,
            $this->profileId,
            ResumeTemplate::PROFESSIONAL,
            'محتوى معاد',
            $generatedAt,
        );

        $this->assertSame('محتوى معاد', $resume->content());
        $this->assertSame($generatedAt, $resume->generatedAt());
    }
}
