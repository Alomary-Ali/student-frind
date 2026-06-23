<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\Events\AlertCreated;
use Modules\Academic\Domain\Events\AlertResolved;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class AcademicAlertEntityTest extends TestCase
{
    public function test_alert_can_be_created(): void
    {
        $alert = AcademicAlert::create(
            AlertId::generate(),
            StudentId::generate(),
            AlertType::LowGpa,
            AlertSeverity::High,
            'Your GPA is below 2.0',
        );

        $this->assertSame(AlertType::LowGpa, $alert->alertType());
        $this->assertSame(AlertSeverity::High, $alert->severity());
        $this->assertSame('Your GPA is below 2.0', $alert->message());
        $this->assertFalse($alert->isResolved());
        $this->assertNull($alert->resolvedAt());
        $this->assertNull($alert->resolvedBy());

        $events = $alert->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(AlertCreated::class, $events[0]);
    }

    public function test_alert_can_be_reconstituted(): void
    {
        $alert = AcademicAlert::reconstitute(
            AlertId::generate(),
            StudentId::generate(),
            AlertType::GraduationDelay,
            AlertSeverity::Critical,
            'Graduation delayed',
            null,
            false,
            new DateTimeImmutable('2026-01-01'),
            null,
            null,
        );

        $this->assertSame(AlertType::GraduationDelay, $alert->alertType());
        $this->assertSame(AlertSeverity::Critical, $alert->severity());
        $this->assertFalse($alert->isResolved());
        $this->assertCount(0, $alert->releaseEvents());
    }

    public function test_alert_can_be_resolved(): void
    {
        $alert = AcademicAlert::create(
            AlertId::generate(),
            StudentId::generate(),
            AlertType::LowGpa,
            AlertSeverity::Medium,
            'Low GPA warning',
        );
        $alert->releaseEvents();

        $alert->resolve('admin-123');

        $this->assertTrue($alert->isResolved());
        $this->assertSame('admin-123', $alert->resolvedBy());
        $this->assertInstanceOf(DateTimeImmutable::class, $alert->resolvedAt());

        $events = $alert->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(AlertResolved::class, $events[0]);
    }

    public function test_resolving_already_resolved_alert_does_not_raise_event(): void
    {
        $alert = AcademicAlert::create(
            AlertId::generate(),
            StudentId::generate(),
            AlertType::LowGpa,
            AlertSeverity::Low,
            'Test',
        );
        $alert->releaseEvents();
        $alert->resolve('admin-123');
        $alert->releaseEvents();

        $alert->resolve('admin-456');

        $this->assertTrue($alert->isResolved());
        $this->assertSame('admin-123', $alert->resolvedBy());
        $this->assertCount(0, $alert->releaseEvents());
    }

    public function test_alert_can_store_metadata(): void
    {
        $metadata = ['gpa' => 1.5, 'threshold' => 2.0];
        $alert = AcademicAlert::create(
            AlertId::generate(),
            StudentId::generate(),
            AlertType::LowGpa,
            AlertSeverity::Critical,
            'Critical GPA alert',
            $metadata,
        );

        $this->assertSame($metadata, $alert->metadata());
    }

    public function test_alert_getters_return_correct_values(): void
    {
        $id = AlertId::generate();
        $studentId = StudentId::generate();
        $createdAt = new DateTimeImmutable('2026-01-01');
        $resolvedAt = new DateTimeImmutable('2026-02-01');

        $alert = AcademicAlert::reconstitute(
            $id,
            $studentId,
            AlertType::CreditDeficit,
            AlertSeverity::Medium,
            'Credit deficit alert',
            ['credits' => 12],
            true,
            $createdAt,
            $resolvedAt,
            'admin-123',
        );

        $this->assertTrue($id->equals($alert->id()));
        $this->assertTrue($studentId->equals($alert->studentId()));
        $this->assertSame(AlertType::CreditDeficit, $alert->alertType());
        $this->assertSame(AlertSeverity::Medium, $alert->severity());
        $this->assertSame('Credit deficit alert', $alert->message());
        $this->assertSame(['credits' => 12], $alert->metadata());
        $this->assertTrue($alert->isResolved());
        $this->assertSame($createdAt, $alert->createdAt());
        $this->assertSame($resolvedAt, $alert->resolvedAt());
        $this->assertSame('admin-123', $alert->resolvedBy());
    }
}
