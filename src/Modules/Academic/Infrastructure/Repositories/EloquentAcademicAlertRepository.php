<?php

declare(strict_types=1);

namespace Modules\Academic\Infrastructure\Repositories;

use DateTimeImmutable;
use Modules\Academic\Domain\Contracts\AcademicAlertRepositoryInterface;
use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Infrastructure\Persistence\EloquentAcademicAlert;

final class EloquentAcademicAlertRepository implements AcademicAlertRepositoryInterface
{
    public function findById(AlertId $id): ?AcademicAlert
    {
        $model = EloquentAcademicAlert::find($id->value());

        return $model ? $this->toDomain($model) : null;
    }

    public function save(AcademicAlert $alert): void
    {
        EloquentAcademicAlert::updateOrCreate(
            ['id' => $alert->id()->value()],
            [
                'student_id' => $alert->studentId()->value(),
                'alert_type' => $alert->alertType()->value,
                'severity' => $alert->severity()->value,
                'message' => $alert->message(),
                'metadata' => $alert->metadata(),
                'is_resolved' => $alert->isResolved(),
                'resolved_at' => $alert->resolvedAt()?->format('Y-m-d H:i:s'),
                'resolved_by' => $alert->resolvedBy(),
            ],
        );
    }

    public function findByStudentId(StudentId $studentId): array
    {
        return EloquentAcademicAlert::where('student_id', $studentId->value())
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($m) => $this->toDomain($m))
            ->all();
    }

    public function findUnresolvedByStudentId(StudentId $studentId): array
    {
        $alerts = [];
        foreach (EloquentAcademicAlert::where('student_id', $studentId->value())
            ->where('is_resolved', false)
            ->orderByDesc('created_at')
            ->get() as $model) {
            try {
                $alerts[] = $this->toDomain($model);
            } catch (\ValueError $e) {
                // Skip alerts with invalid enum values
                continue;
            }
        }

        return $alerts;
    }

    public function existsForStudentAndType(StudentId $studentId, string $alertType): bool
    {
        return EloquentAcademicAlert::where('student_id', $studentId->value())
            ->where('alert_type', $alertType)
            ->where('is_resolved', false)
            ->exists();
    }

    private function toDomain(EloquentAcademicAlert $model): AcademicAlert
    {
        // Handle metadata that might be already an array or a JSON string
        $metadata = null;
        if ($model->metadata !== null) {
            if (is_array($model->metadata)) {
                $metadata = $model->metadata;
            } else {
                $decoded = json_decode($model->metadata, true);
                $metadata = is_array($decoded) ? $decoded : null;
            }
        }

        return AcademicAlert::reconstitute(
            id: AlertId::fromString($model->id),
            studentId: StudentId::fromString($model->student_id),
            alertType: AlertType::from($model->alert_type),
            severity: AlertSeverity::from($model->severity),
            message: $model->message,
            metadata: $metadata,
            isResolved: (bool) $model->is_resolved,
            createdAt: new DateTimeImmutable($model->created_at->toIso8601String()),
            resolvedAt: $model->resolved_at ? new DateTimeImmutable($model->resolved_at->toIso8601String()) : null,
            resolvedBy: $model->resolved_by,
        );
    }
}
