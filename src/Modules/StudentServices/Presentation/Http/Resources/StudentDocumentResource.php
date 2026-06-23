<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\StudentDocumentDto;

final class StudentDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var StudentDocumentDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'student_id' => $dto->studentId,
            'type' => $dto->type,
            'title' => $dto->title,
            'file_path' => $dto->filePath,
            'status' => $dto->status,
            'verification_code' => $dto->verificationCode,
            'metadata' => $dto->metadata,
            'created_at' => $dto->createdAt,
        ];
    }
}
