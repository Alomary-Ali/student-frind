<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\DocumentRequestRepositoryInterface;
use Modules\StudentServices\Domain\Entities\DocumentRequest;
use Modules\StudentServices\Domain\Enums\DocumentType;
use Modules\StudentServices\Domain\ValueObjects\DocumentRequestId;

final readonly class RequestDocument
{
    public function __construct(
        private DocumentRequestRepositoryInterface $requests,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $studentId, string $documentType, ?string $notes = null): array
    {
        $request = DocumentRequest::create(
            id: DocumentRequestId::generate(),
            studentId: $studentId,
            documentType: DocumentType::from($documentType),
            notes: $notes,
        );

        $this->requests->save($request);
        $this->events->dispatch($request->releaseEvents());

        return [
            'id' => $request->id()->value(),
            'student_id' => $request->studentId(),
            'document_type' => $request->documentType()->value,
            'status' => $request->status()->value,
            'notes' => $request->notes(),
            'created_at' => $request->createdAt()->format('c'),
        ];
    }
}
