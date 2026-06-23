<?php
declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;
use Modules\StudentServices\Domain\Entities\StudentDocument;
use Modules\StudentServices\Domain\Enums\DocumentType;
use Modules\StudentServices\Domain\ValueObjects\DocumentId;
use Modules\StudentServices\Infrastructure\Integrations\DocumentGeneratorInterface;
use Ramsey\Uuid\Uuid;

final readonly class GenerateDocument
{
    public function __construct(
        private DocumentRepositoryInterface $documents,
        private DocumentGeneratorInterface $generator,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $studentId, string $documentType, array $data = []): array
    {
        $type = DocumentType::from($documentType);

        $document = StudentDocument::create(
            id: DocumentId::generate(),
            studentId: $studentId,
            type: $type,
            title: $data['title'] ?? $type->label(),
            metadata: $data,
        );

        $filePath = $this->generator->generate($documentType, $data);
        $verificationCode = Uuid::uuid4()->toString();

        $document->generate($filePath, $verificationCode);

        $this->documents->save($document);
        $this->events->dispatch($document->releaseEvents());

        return [
            'id' => $document->id()->value(),
            'student_id' => $document->studentId(),
            'type' => $document->type()->value,
            'title' => $document->title(),
            'file_path' => $document->filePath(),
            'status' => $document->status()->value,
            'verification_code' => $document->verificationCode(),
            'created_at' => $document->createdAt()->format('c'),
        ];
    }
}
