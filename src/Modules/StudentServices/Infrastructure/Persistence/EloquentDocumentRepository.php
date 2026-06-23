<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;
use Modules\StudentServices\Domain\Entities\StudentDocument;
use Modules\StudentServices\Domain\Enums\DocumentStatus;
use Modules\StudentServices\Domain\Enums\DocumentType;
use Modules\StudentServices\Domain\ValueObjects\DocumentId;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentStudentDocument;

final class EloquentDocumentRepository implements DocumentRepositoryInterface
{
    public function findById(DocumentId $id): ?StudentDocument
    {
        $model = EloquentStudentDocument::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(string $studentId): array
    {
        return EloquentStudentDocument::where('student_id', $studentId)
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByVerificationCode(string $code): ?StudentDocument
    {
        $model = EloquentStudentDocument::where('verification_code', $code)->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(StudentDocument $document): void
    {
        $model = EloquentStudentDocument::find($document->id()->value());

        if ($model === null) {
            $model = new EloquentStudentDocument;
            $model->id = $document->id()->value();
        }

        $model->student_id = $document->studentId();
        $model->type = $document->type()->value;
        $model->title = $document->title();
        $model->file_path = $document->filePath();
        $model->status = $document->status()->value;
        $model->verification_code = $document->verificationCode();
        $model->metadata = $document->metadata();
        $model->save();
    }

    private function toEntity(EloquentStudentDocument $model): StudentDocument
    {
        return StudentDocument::reconstitute(
            id: DocumentId::of($model->id),
            studentId: $model->student_id,
            type: DocumentType::from($model->type),
            title: $model->title,
            filePath: $model->file_path,
            status: DocumentStatus::from($model->status),
            verificationCode: $model->verification_code,
            metadata: $model->metadata ?? [],
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
