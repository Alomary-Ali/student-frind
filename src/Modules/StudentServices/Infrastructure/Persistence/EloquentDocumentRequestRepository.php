<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Contracts\DocumentRequestRepositoryInterface;
use Modules\StudentServices\Domain\Entities\DocumentRequest;
use Modules\StudentServices\Domain\Enums\DocumentStatus;
use Modules\StudentServices\Domain\Enums\DocumentType;
use Modules\StudentServices\Domain\ValueObjects\DocumentRequestId;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentDocumentRequest;

final class EloquentDocumentRequestRepository implements DocumentRequestRepositoryInterface
{
    public function findById(DocumentRequestId $id): ?DocumentRequest
    {
        $model = EloquentDocumentRequest::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(string $studentId): array
    {
        return EloquentDocumentRequest::where('student_id', $studentId)
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function save(DocumentRequest $request): void
    {
        $model = EloquentDocumentRequest::find($request->id()->value());

        if ($model === null) {
            $model = new EloquentDocumentRequest;
            $model->id = $request->id()->value();
        }

        $model->student_id = $request->studentId();
        $model->document_type = $request->documentType()->value;
        $model->status = $request->status()->value;
        $model->notes = $request->notes();
        $model->save();
    }

    private function toEntity(EloquentDocumentRequest $model): DocumentRequest
    {
        return DocumentRequest::reconstitute(
            id: DocumentRequestId::of($model->id),
            studentId: $model->student_id,
            documentType: DocumentType::from($model->document_type),
            status: DocumentStatus::from($model->status),
            notes: $model->notes,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
