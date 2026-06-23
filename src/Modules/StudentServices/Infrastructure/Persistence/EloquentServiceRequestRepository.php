<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\Entities\ServiceRequest;
use Modules\StudentServices\Domain\Enums\RequestPriority;
use Modules\StudentServices\Domain\Enums\ServiceStatus;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentServiceRequest;

final class EloquentServiceRequestRepository implements ServiceRequestRepositoryInterface
{
    public function findById(ServiceRequestId $id): ?ServiceRequest
    {
        $model = EloquentServiceRequest::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(string $studentId): array
    {
        return EloquentServiceRequest::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByStatus(string $status): array
    {
        return EloquentServiceRequest::where('status', $status)
            ->get()
            ->map(fn ($model) => $this->toEntity($model))
            ->toArray();
    }

    public function findByRefNumber(string $refNumber): ?ServiceRequest
    {
        $model = EloquentServiceRequest::where('ref_number', $refNumber)->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(ServiceRequest $request): void
    {
        $model = EloquentServiceRequest::find($request->id()->value());

        if ($model === null) {
            $model = new EloquentServiceRequest;
            $model->id = $request->id()->value();
        }

        $model->ref_number = $request->refNumber();
        $model->category_id = $request->categoryId();
        $model->student_id = $request->studentId();
        $model->status = $request->status()->value;
        $model->priority = $request->priority()->value;
        $model->notes = $request->notes();
        $model->admin_notes = $request->adminNotes();
        $model->workflow_id = $request->workflowId();
        $model->current_step_id = $request->currentStepId();
        $model->attachments = $request->attachments();
        $model->save();
    }

    public function nextRefNumber(): string
    {
        $datePart = date('Ymd');
        $count = EloquentServiceRequest::where('ref_number', 'like', 'SRV-' . $datePart . '-%')->count();

        return 'SRV-' . $datePart . '-' . ($count + 1);
    }

    private function toEntity(EloquentServiceRequest $model): ServiceRequest
    {
        return ServiceRequest::reconstitute(
            id: ServiceRequestId::of($model->id),
            studentId: $model->student_id,
            categoryId: $model->category_id,
            refNumber: $model->ref_number,
            status: ServiceStatus::from($model->status),
            priority: RequestPriority::from($model->priority),
            notes: $model->notes,
            adminNotes: $model->admin_notes,
            workflowId: $model->workflow_id,
            currentStepId: $model->current_step_id,
            attachments: $model->attachments ?? [],
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
