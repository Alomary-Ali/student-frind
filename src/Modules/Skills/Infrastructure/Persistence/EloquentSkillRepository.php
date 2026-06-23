<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Skills\Domain\Contracts\SkillRepositoryInterface;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentSkill;

final class EloquentSkillRepository implements SkillRepositoryInterface
{
    public function findById(SkillId $id): ?Skill
    {
        $model = EloquentSkill::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(Skill $skill): void
    {
        $model = EloquentSkill::find($skill->id()->value());

        if ($model === null) {
            $model = new EloquentSkill();
            $model->id = $skill->id()->value();
        }

        $model->skill_profile_id = $skill->skillProfileId()->value();
        $model->name = $skill->name();
        $model->category = $skill->category()->value;
        $model->level = $skill->level()->value;
        $model->years_of_experience = $skill->yearsOfExperience();
        $model->last_used = $skill->lastUsed()->format('Y-m-d H:i:s');
        $model->save();
    }

    public function delete(SkillId $id): void
    {
        EloquentSkill::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentSkill $model): Skill
    {
        return Skill::reconstitute(
            id: SkillId::of($model->id),
            skillProfileId: SkillProfileId::of($model->skill_profile_id),
            name: $model->name,
            category: SkillCategory::from($model->category),
            level: SkillLevel::from($model->level),
            yearsOfExperience: (int) $model->years_of_experience,
            lastUsed: new DateTimeImmutable($model->last_used->format('Y-m-d H:i:s'))
        );
    }
}
