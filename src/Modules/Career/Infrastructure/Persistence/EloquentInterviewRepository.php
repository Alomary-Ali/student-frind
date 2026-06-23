<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;
use Modules\Career\Domain\Entities\Interview;
use Modules\Career\Domain\Enums\InterviewStatus;
use Modules\Career\Domain\Enums\InterviewType;
use Modules\Career\Domain\ValueObjects\InterviewId;
use Modules\Career\Infrastructure\Persistence\Eloquent\EloquentInterview;
use Modules\Career\Infrastructure\Persistence\Eloquent\EloquentInterviewQuestion;
use Ramsey\Uuid\Uuid;

final class EloquentInterviewRepository implements InterviewRepositoryInterface
{
    public function findById(InterviewId $id): ?Interview
    {
        $model = EloquentInterview::with('questions')->find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(string $studentId): array
    {
        $models = EloquentInterview::with('questions')
            ->where('student_id', $studentId)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return $models->map(fn ($model) => $this->toEntity($model))->toArray();
    }

    public function save(Interview $interview): void
    {
        $model = EloquentInterview::find($interview->id()->value());

        if ($model === null) {
            $model = new EloquentInterview;
            $model->id = $interview->id()->value();
        }

        $model->student_id = $interview->studentId();
        $model->type = $interview->type()->value;
        $model->status = $interview->status()->value;
        $model->scheduled_at = $interview->scheduledAt()->format('Y-m-d H:i:s');
        $model->score = $interview->score();
        $model->feedback = $interview->feedback();
        $model->save();

        // Sync questions (stored as arrays in entity, persisted as models)
        $questions = $interview->questions();
        $currentIds = [];

        foreach ($questions as $question) {
            $qId = $question['id'] ?? Uuid::uuid4()->toString();
            $currentIds[] = $qId;

            $qModel = EloquentInterviewQuestion::find($qId) ?? new EloquentInterviewQuestion;
            $qModel->id = $qId;
            $qModel->interview_id = $interview->id()->value();
            $qModel->question = $question['question'] ?? '';
            $qModel->category = $question['category'] ?? null;
            $qModel->order = $question['order'] ?? 0;
            $qModel->save();
        }

        EloquentInterviewQuestion::where('interview_id', $interview->id()->value())
            ->whereNotIn('id', $currentIds)
            ->delete();
    }

    public function delete(InterviewId $id): void
    {
        EloquentInterview::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentInterview $model): Interview
    {
        $questions = [];
        foreach ($model->questions as $q) {
            $questions[] = [
                'id' => $q->id,
                'question' => $q->question,
                'category' => $q->category,
                'order' => (int) $q->order,
            ];
        }

        return Interview::reconstitute(
            id: InterviewId::of($model->id),
            studentId: $model->student_id,
            type: InterviewType::from($model->type),
            status: InterviewStatus::from($model->status),
            scheduledAt: new DateTimeImmutable($model->scheduled_at->format('Y-m-d H:i:s')),
            questions: $questions,
            score: $model->score !== null ? (int) $model->score : null,
            feedback: $model->feedback,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s')),
        );
    }
}
