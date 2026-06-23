<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Contracts\AchievementRepositoryInterface;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\ValueObjects\AchievementId;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentAchievement;

final class EloquentAchievementRepository implements AchievementRepositoryInterface
{
    public function findById(AchievementId $id): ?Achievement
    {
        $model = EloquentAchievement::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(StudentId $studentId): array
    {
        $models = EloquentAchievement::where('student_id', $studentId->value())
            ->orderBy('unlocked_at', 'desc')
            ->get();

        $achievements = [];
        foreach ($models as $model) {
            $achievements[] = $this->toEntity($model);
        }

        return $achievements;
    }

    public function save(Achievement $achievement): void
    {
        $model = EloquentAchievement::find($achievement->id()->value());

        if ($model === null) {
            $model = new EloquentAchievement;
            $model->id = $achievement->id()->value();
        }

        $model->student_id = $achievement->studentId()->value();
        $model->type = $achievement->type()->value;
        $model->title = $achievement->title();
        $model->description = $achievement->description();
        $model->badge_url = $achievement->badgeUrl();
        $model->unlocked_at = $achievement->unlockedAt()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function delete(AchievementId $id): void
    {
        EloquentAchievement::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentAchievement $model): Achievement
    {
        return Achievement::reconstitute(
            id: AchievementId::of($model->id),
            studentId: StudentId::of($model->student_id),
            type: AchievementType::from($model->type),
            title: $model->title,
            description: $model->description,
            badgeUrl: $model->badge_url,
            unlockedAt: new DateTimeImmutable($model->unlocked_at->format('Y-m-d H:i:s')),
        );
    }
}
