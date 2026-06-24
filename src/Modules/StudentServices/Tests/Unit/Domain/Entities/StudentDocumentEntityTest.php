<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Unit\Domain\Entities;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Entities\StudentDocument;
use Modules\StudentServices\Domain\Enums\DocumentStatus;
use Modules\StudentServices\Domain\Enums\DocumentType;
use Modules\StudentServices\Domain\Events\DocumentGenerated;
use Modules\StudentServices\Domain\Events\DocumentVerified;
use Modules\StudentServices\Domain\ValueObjects\DocumentId;
use PHPUnit\Framework\TestCase;

final class StudentDocumentEntityTest extends TestCase
{
    public function test_create_returns_document_with_pending_status(): void
    {
        $id = DocumentId::generate();
        $document = StudentDocument::create($id, 'student-1', DocumentType::CERTIFICATE, 'شهادة تخرج');

        $this->assertSame($id, $document->id());
        $this->assertSame('student-1', $document->studentId());
        $this->assertSame(DocumentType::CERTIFICATE, $document->type());
        $this->assertSame('شهادة تخرج', $document->title());
        $this->assertNull($document->filePath());
        $this->assertSame(DocumentStatus::PENDING, $document->status());
        $this->assertNull($document->verificationCode());
        $this->assertEmpty($document->metadata());
    }

    public function test_generate_updates_status_and_dispatches_event(): void
    {
        $id = DocumentId::generate();
        $document = StudentDocument::create($id, 'student-1', DocumentType::TRANSCRIPT, 'كشف درجات');

        $document->generate('/path/to/transcript.pdf', 'VER-12345');

        $this->assertSame('/path/to/transcript.pdf', $document->filePath());
        $this->assertSame('VER-12345', $document->verificationCode());
        $this->assertSame(DocumentStatus::GENERATED, $document->status());

        $events = $document->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(DocumentGenerated::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->documentId);
        $this->assertSame('/path/to/transcript.pdf', $events[0]->filePath);
    }

    public function test_verify_updates_status_and_dispatches_event(): void
    {
        $id = DocumentId::generate();
        $document = StudentDocument::create($id, 'student-1', DocumentType::CERTIFICATE, 'شهادة');
        $document->generate('/path/to/cert.pdf', 'VER-123');
        $document->releaseEvents();

        $document->verify('admin-1');

        $this->assertSame(DocumentStatus::VERIFIED, $document->status());

        $events = $document->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(DocumentVerified::class, $events[0]);
        $this->assertSame($id->value(), $events[0]->documentId);
        $this->assertSame('admin-1', $events[0]->verifierId);
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = DocumentId::generate();
        $now = new DateTimeImmutable;

        $document = StudentDocument::reconstitute(
            id: $id,
            studentId: 'student-1',
            type: DocumentType::STATEMENT,
            title: 'بيان درجات',
            filePath: '/path/to/statement.pdf',
            status: DocumentStatus::VERIFIED,
            verificationCode: 'VER-999',
            metadata: ['semester' => '2026-1'],
            createdAt: $now,
            updatedAt: $now,
        );

        $this->assertSame($id->value(), $document->id()->value());
        $this->assertSame(DocumentStatus::VERIFIED, $document->status());
        $this->assertSame('VER-999', $document->verificationCode());
        $this->assertSame(['semester' => '2026-1'], $document->metadata());
    }

    public function test_release_events_clears_events(): void
    {
        $document = StudentDocument::create(DocumentId::generate(), 'student-1', DocumentType::CERTIFICATE, 'شهادة');
        $document->generate('/path.pdf', 'VER-123');

        $this->assertCount(1, $document->releaseEvents());
        $this->assertCount(0, $document->releaseEvents());
    }
}
