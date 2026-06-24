<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Entities\ServiceRequest;
use Modules\StudentServices\Domain\Enums\RequestPriority;
use Modules\StudentServices\Domain\Enums\ServiceStatus;
use Modules\StudentServices\Domain\Events\ServiceRequestApproved;
use Modules\StudentServices\Domain\Events\ServiceRequestCancelled;
use Modules\StudentServices\Domain\Events\ServiceRequestCompleted;
use Modules\StudentServices\Domain\Events\ServiceRequestRejected;
use Modules\StudentServices\Domain\Events\ServiceRequestReviewed;
use Modules\StudentServices\Domain\Events\ServiceRequestSubmitted;
use Modules\StudentServices\Domain\Exceptions\InvalidServiceStatusTransitionException;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;
use PHPUnit\Framework\TestCase;

final class ServiceRequestEntityTest extends TestCase
{
    public function test_create_returns_request_with_new_status(): void
    {
        $id = ServiceRequestId::generate();
        $request = ServiceRequest::create($id, 'student-1', 'category-1', 'REF-001', RequestPriority::MEDIUM, 'Test notes');

        $this->assertSame($id, $request->id());
        $this->assertSame('student-1', $request->studentId());
        $this->assertSame('category-1', $request->categoryId());
        $this->assertSame('REF-001', $request->refNumber());
        $this->assertSame(ServiceStatus::NEW, $request->status());
        $this->assertSame(RequestPriority::MEDIUM, $request->priority());
        $this->assertSame('Test notes', $request->notes());
        $this->assertNull($request->adminNotes());
        $this->assertNull($request->workflowId());
        $this->assertEmpty($request->attachments());
    }

    public function test_create_dispatches_submitted_event(): void
    {
        $id = ServiceRequestId::generate();
        $request = ServiceRequest::create($id, 'student-1', 'category-1', 'REF-001');

        $events = $request->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ServiceRequestSubmitted::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->serviceRequestId);
        $this->assertSame('student-1', $events[0]->studentId);
    }

    public function test_submit_for_review_changes_status_and_dispatches_event(): void
    {
        $id = ServiceRequestId::generate();
        $request = ServiceRequest::create($id, 'student-1', 'category-1', 'REF-001');
        $request->releaseEvents();

        $request->submitForReview('reviewer-1', 'Review notes');

        $this->assertSame(ServiceStatus::UNDER_REVIEW, $request->status());
        $this->assertSame('Review notes', $request->adminNotes());

        $events = $request->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ServiceRequestReviewed::class, $events[0]);
    }

    public function test_approve_changes_status_and_dispatches_event(): void
    {
        $id = ServiceRequestId::generate();
        $request = ServiceRequest::create($id, 'student-1', 'category-1', 'REF-001');
        $request->submitForReview('reviewer-1');
        $request->releaseEvents();

        $request->approve('reviewer-1');

        $this->assertSame(ServiceStatus::APPROVED, $request->status());

        $events = $request->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ServiceRequestApproved::class, $events[0]);
    }

    public function test_reject_changes_status_and_dispatches_event(): void
    {
        $id = ServiceRequestId::generate();
        $request = ServiceRequest::create($id, 'student-1', 'category-1', 'REF-001');
        $request->submitForReview('reviewer-1');
        $request->releaseEvents();

        $request->reject('reviewer-1', 'Incomplete information');

        $this->assertSame(ServiceStatus::REJECTED, $request->status());

        $events = $request->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ServiceRequestRejected::class, $events[0]);
        $this->assertSame('Incomplete information', $events[0]->reason);
    }

    public function test_complete_changes_status_and_dispatches_event(): void
    {
        $id = ServiceRequestId::generate();
        $request = ServiceRequest::create($id, 'student-1', 'category-1', 'REF-001');
        $request->submitForReview('reviewer-1');
        $request->approve('reviewer-1');
        $request->releaseEvents();

        $request->complete();

        $this->assertSame(ServiceStatus::COMPLETED, $request->status());

        $events = $request->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ServiceRequestCompleted::class, $events[0]);
    }

    public function test_cancel_changes_status_and_dispatches_event(): void
    {
        $id = ServiceRequestId::generate();
        $request = ServiceRequest::create($id, 'student-1', 'category-1', 'REF-001');
        $request->releaseEvents();

        $request->cancel('Student request');

        $this->assertSame(ServiceStatus::CANCELLED, $request->status());

        $events = $request->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ServiceRequestCancelled::class, $events[0]);
    }

    public function test_invalid_transition_throws_exception(): void
    {
        $request = ServiceRequest::create(ServiceRequestId::generate(), 'student-1', 'category-1', 'REF-001');

        $this->expectException(InvalidServiceStatusTransitionException::class);
        $request->approve('reviewer-1');
    }

    public function test_add_note_updates_notes(): void
    {
        $request = ServiceRequest::create(ServiceRequestId::generate(), 'student-1', 'category-1', 'REF-001');

        $request->addNote('Updated notes');

        $this->assertSame('Updated notes', $request->notes());
    }

    public function test_add_attachment_appends_to_attachments(): void
    {
        $request = ServiceRequest::create(ServiceRequestId::generate(), 'student-1', 'category-1', 'REF-001');

        $request->addAttachment('/path/to/file1.pdf');
        $request->addAttachment('/path/to/file2.pdf');

        $this->assertCount(2, $request->attachments());
        $this->assertSame('/path/to/file1.pdf', $request->attachments()[0]);
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = ServiceRequestId::generate();
        $now = new DateTimeImmutable;

        $request = ServiceRequest::reconstitute(
            id: $id,
            studentId: 'student-1',
            categoryId: 'category-1',
            refNumber: 'REF-001',
            status: ServiceStatus::APPROVED,
            priority: RequestPriority::HIGH,
            notes: 'Test notes',
            adminNotes: 'Admin notes',
            workflowId: 'workflow-1',
            currentStepId: 'step-1',
            attachments: ['/path/to/file.pdf'],
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id->value(), $request->id()->value());
        $this->assertSame(ServiceStatus::APPROVED, $request->status());
        $this->assertSame('Test notes', $request->notes());
    }

    public function test_release_events_clears_events(): void
    {
        $request = ServiceRequest::create(ServiceRequestId::generate(), 'student-1', 'category-1', 'REF-001');

        $this->assertCount(1, $request->releaseEvents());
        $this->assertCount(0, $request->releaseEvents());
    }
}
