<?php

declare(strict_types=1);

namespace Modules\Opportunities\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Opportunities\Domain\Contracts\RecommendationRepositoryInterface;
use Modules\Opportunities\Domain\Entities\Recommendation;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Opportunities\Domain\ValueObjects\OpportunityScore;
use Modules\Opportunities\Domain\ValueObjects\RecommendationId;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentRecommendation;

final class EloquentRecommendationRepository implements RecommendationRepositoryInterface
{
    public function findByStudentId(string $studentId): array
    {
        $models = EloquentRecommendation::where('student_id', $studentId)
            ->orderBy('score', 'desc')
            ->get();

        return $models->map(fn (EloquentRecommendation $model) => $this->toEntity($model))->toArray();
    }

    public function findTopByStudentId(string $studentId, int $limit = 10): array
    {
        $models = EloquentRecommendation::where('student_id', $studentId)
            ->orderBy('score', 'desc')
            ->limit($limit)
            ->get();

        return $models->map(fn (EloquentRecommendation $model) => $this->toEntity($model))->toArray();
    }

    public function findByOpportunityAndStudent(string $studentId, string $opportunityId): ?Recommendation
    {
        $model = EloquentRecommendation::where('student_id', $studentId)
            ->where('opportunity_id', $opportunityId)
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function save(Recommendation $recommendation): void
    {
        $model = EloquentRecommendation::find($recommendation->id()->value());

        if ($model === null) {
            $model = new EloquentRecommendation;
            $model->id = $recommendation->id()->value();
        }

        $model->student_id = $recommendation->studentId();
        $model->opportunity_id = $recommendation->opportunityId()->value();
        $model->score = $recommendation->score()->value();
        $model->reason = $recommendation->reason();
        $model->generated_at = $recommendation->generatedAt()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function delete(RecommendationId $id): void
    {
        EloquentRecommendation::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentRecommendation $model): Recommendation
    {
        return Recommendation::reconstitute(
            id: RecommendationId::of($model->id),
            studentId: $model->student_id,
            opportunityId: OpportunityId::of($model->opportunity_id),
            score: OpportunityScore::fromFloat((float) $model->score),
            reason: $model->reason,
            generatedAt: new DateTimeImmutable($model->generated_at->format('Y-m-d H:i:s')),
        );
    }
}
