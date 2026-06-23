<?php

declare(strict_types=1);

namespace Modules\Opportunities\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\Entities\OpportunityApplication;
use Modules\Opportunities\Domain\Entities\Recommendation;
use Modules\Opportunities\Domain\Entities\SavedOpportunity;
use Modules\Opportunities\Domain\Enums\ApplicationStatus;
use Modules\Opportunities\Domain\Enums\OpportunityStatus;
use Modules\Opportunities\Domain\Enums\Provider;
use Modules\Opportunities\Domain\Events\OpportunityCreated;
use Modules\Opportunities\Domain\Events\OpportunityUpdated;
use Modules\Opportunities\Domain\ValueObjects\ApplicationId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityScore;
use Modules\Opportunities\Domain\ValueObjects\RecommendationId;
use PHPUnit\Framework\TestCase;

final class OpportunityEntityTest extends TestCase
{
    public function test_can_create_job_opportunity(): void
    {
        $id = OpportunityId::generate();
        $opportunity = Opportunity::createJob(
            id: $id,
            title: 'مطور ويب',
            description: 'نبحث عن مطور ويب بخبرة في Laravel',
            provider: Provider::LINKEDIN,
            location: 'الرياض',
            country: 'السعودية',
            deadline: new DateTimeImmutable('+30 days'),
            applyUrl: 'https://example.com/apply',
            company: 'شركة التقنية',
            salaryMin: 5000,
            salaryMax: 15000,
            employmentType: 'full_time',
            locationType: 'on_site',
            tags: ['تطوير', 'ويب'],
        );

        $this->assertSame($id, $opportunity->id());
        $this->assertSame('مطور ويب', $opportunity->title());
        $this->assertSame('job', $opportunity->type()->value);
        $this->assertSame('active', $opportunity->status()->value);
        $this->assertSame('الرياض', $opportunity->location());
        $this->assertSame('السعودية', $opportunity->country());
        $this->assertSame('شركة التقنية', $opportunity->metadata()['company']);
        $this->assertSame(5000.0, $opportunity->metadata()['salary_min']);
        $this->assertSame(15000.0, $opportunity->metadata()['salary_max']);
        $this->assertFalse($opportunity->isExpired());
    }

    public function test_can_create_scholarship_opportunity(): void
    {
        $id = OpportunityId::generate();
        $opportunity = Opportunity::createScholarship(
            id: $id,
            title: 'منحة لدراسة الماجستير',
            description: 'منحة كاملة لدراسة علوم الحاسب',
            provider: Provider::MANUAL,
            location: 'بوسطن',
            country: 'الولايات المتحدة',
            deadline: new DateTimeImmutable('+60 days'),
            applyUrl: 'https://university.edu/scholarship',
            university: 'MIT',
            programLevel: 'master',
            coverageAmount: 50000,
            coverageCurrency: 'USD',
            tags: ['منحة', 'ماجستير'],
        );

        $this->assertSame('scholarship', $opportunity->type()->value);
        $this->assertSame('MIT', $opportunity->metadata()['university']);
        $this->assertSame('master', $opportunity->metadata()['program_level']);
        $this->assertSame(50000.0, $opportunity->metadata()['coverage_amount']);
    }

    public function test_can_reconstitute_opportunity(): void
    {
        $id = OpportunityId::generate();
        $now = new DateTimeImmutable;
        $opportunity = Opportunity::reconstitute(
            id: $id,
            title: 'دورة برمجة',
            description: 'دورة تعلم Python',
            provider: Provider::COURSERA,
            type: \Modules\Opportunities\Domain\Enums\OpportunityType::COURSE,
            location: null,
            country: null,
            deadline: null,
            applyUrl: 'https://coursera.org/course',
            status: OpportunityStatus::ACTIVE,
            metadata: ['platform' => 'Coursera', 'duration_hours' => 40],
            sourceUrl: null,
            imageUrl: null,
            tags: ['برمجة'],
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id, $opportunity->id());
        $this->assertSame('دورة برمجة', $opportunity->title());
        $this->assertSame('course', $opportunity->type()->value);
        $this->assertSame('Coursera', $opportunity->metadata()['platform']);
    }

    public function test_can_update_opportunity_details(): void
    {
        $id = OpportunityId::generate();
        $opportunity = Opportunity::createJob(
            id: $id,
            title: 'مطور ويب',
            description: 'وصف قديم',
            provider: Provider::LINKEDIN,
            location: 'الرياض',
            country: 'السعودية',
            deadline: null,
            applyUrl: 'https://example.com',
            company: 'شركة',
            salaryMin: null,
            salaryMax: null,
            employmentType: null,
            locationType: null,
        );

        $opportunity->updateDetails(
            title: 'مطور Laravel',
            description: 'وصف جديد',
            location: 'جدة',
            country: 'السعودية',
            deadline: new DateTimeImmutable('+30 days'),
            applyUrl: 'https://example.com/new',
            metadata: ['company' => 'شركة جديدة'],
        );

        $this->assertSame('مطور Laravel', $opportunity->title());
        $this->assertSame('وصف جديد', $opportunity->description());
        $this->assertSame('جدة', $opportunity->location());
    }

    public function test_opportunity_events_on_create(): void
    {
        $id = OpportunityId::generate();
        $opportunity = Opportunity::createJob(
            id: $id,
            title: 'مطور',
            description: 'وصف',
            provider: Provider::LINKEDIN,
            location: null,
            country: null,
            deadline: null,
            applyUrl: 'https://example.com',
            company: null,
            salaryMin: null,
            salaryMax: null,
            employmentType: null,
            locationType: null,
        );

        $events = $opportunity->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(OpportunityCreated::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->opportunityId);
    }

    public function test_opportunity_events_on_update(): void
    {
        $id = OpportunityId::generate();
        $opportunity = Opportunity::createJob(
            id: $id,
            title: 'مطور',
            description: 'وصف',
            provider: Provider::LINKEDIN,
            location: null,
            country: null,
            deadline: null,
            applyUrl: 'https://example.com',
            company: null,
            salaryMin: null,
            salaryMax: null,
            employmentType: null,
            locationType: null,
        );

        $opportunity->releaseEvents();

        $opportunity->updateDetails(
            title: 'مطور جديد',
            description: 'وصف جديد',
            location: null,
            country: null,
            deadline: null,
            applyUrl: 'https://example.com',
            metadata: [],
        );

        $events = $opportunity->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(OpportunityUpdated::class, $events[0]);
    }

    public function test_can_close_opportunity(): void
    {
        $id = OpportunityId::generate();
        $opportunity = Opportunity::createJob(
            id: $id,
            title: 'مطور',
            description: 'وصف',
            provider: Provider::LINKEDIN,
            location: null,
            country: null,
            deadline: null,
            applyUrl: 'https://example.com',
            company: null,
            salaryMin: null,
            salaryMax: null,
            employmentType: null,
            locationType: null,
        );

        $this->assertFalse($opportunity->isExpired());

        $opportunity->close();

        $this->assertTrue($opportunity->isExpired());
        $this->assertSame('closed', $opportunity->status()->value);
    }

    public function test_opportunity_application_lifecycle(): void
    {
        $appId = ApplicationId::generate();
        $oppId = OpportunityId::generate();

        $application = OpportunityApplication::create(
            id: $appId,
            opportunityId: $oppId,
            studentId: 'student-123',
            notes: 'أود التقديم',
        );

        $this->assertSame('saved', $application->status()->value);
        $this->assertNull($application->appliedAt());

        $application->submit();

        $this->assertSame('applied', $application->status()->value);
        $this->assertNotNull($application->appliedAt());
        $this->assertFalse($application->isFinalStatus());

        $application->updateStatus(ApplicationStatus::ACCEPTED);

        $this->assertSame('accepted', $application->status()->value);
        $this->assertTrue($application->isFinalStatus());
    }

    public function test_saved_opportunity_creation(): void
    {
        $oppId = OpportunityId::generate();

        $saved = SavedOpportunity::create(
            studentId: 'student-123',
            opportunityId: $oppId,
        );

        $this->assertSame('student-123', $saved->studentId());
        $this->assertSame($oppId, $saved->opportunityId());
        $this->assertInstanceOf(DateTimeImmutable::class, $saved->savedAt());
    }

    public function test_recommendation_creation(): void
    {
        $recId = RecommendationId::generate();
        $oppId = OpportunityId::generate();

        $recommendation = Recommendation::create(
            id: $recId,
            studentId: 'student-123',
            opportunityId: $oppId,
            score: OpportunityScore::fromFloat(85.5),
            reason: 'تطابق مع المهارات',
        );

        $this->assertSame($recId, $recommendation->id());
        $this->assertSame(85.5, $recommendation->score()->value());
        $this->assertSame('تطابق مع المهارات', $recommendation->reason());
        $this->assertInstanceOf(DateTimeImmutable::class, $recommendation->generatedAt());
    }
}
