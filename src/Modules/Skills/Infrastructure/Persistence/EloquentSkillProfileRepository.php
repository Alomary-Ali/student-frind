<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Entities\Skill;
use Modules\Skills\Domain\Entities\Certification;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Domain\ValueObjects\SkillId;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\Enums\SkillCategory;
use Modules\Skills\Domain\Enums\SkillLevel;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentSkillProfile;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentSkill;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentCertification;

final class EloquentSkillProfileRepository implements SkillProfileRepositoryInterface
{
    public function findById(SkillProfileId $id): ?SkillProfile
    {
        $model = EloquentSkillProfile::with(['skills', 'certifications'])->find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findByStudentId(StudentId $studentId): ?SkillProfile
    {
        $model = EloquentSkillProfile::with(['skills', 'certifications'])
            ->where('student_id', $studentId->value())
            ->first();

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(SkillProfile $profile): void
    {
        $model = EloquentSkillProfile::find($profile->id()->value());

        if ($model === null) {
            $model = new EloquentSkillProfile();
            $model->id = $profile->id()->value();
        }

        $model->student_id = $profile->studentId()->value();
        $model->save();

        // Sync Skills
        $currentSkillIds = array_map(fn(Skill $skill) => $skill->id()->value(), $profile->skills());
        EloquentSkill::where('skill_profile_id', $profile->id()->value())
            ->whereNotIn('id', $currentSkillIds)
            ->delete();

        foreach ($profile->skills() as $skill) {
            $skillModel = EloquentSkill::find($skill->id()->value()) ?? new EloquentSkill();
            $skillModel->id = $skill->id()->value();
            $skillModel->skill_profile_id = $profile->id()->value();
            $skillModel->name = $skill->name();
            $skillModel->category = $skill->category()->value;
            $skillModel->level = $skill->level()->value;
            $skillModel->years_of_experience = $skill->yearsOfExperience();
            $skillModel->last_used = $skill->lastUsed()->format('Y-m-d H:i:s');
            $skillModel->save();
        }

        // Sync Certifications
        $currentCertIds = array_map(fn(Certification $cert) => $cert->id()->value(), $profile->certifications());
        EloquentCertification::where('skill_profile_id', $profile->id()->value())
            ->whereNotIn('id', $currentCertIds)
            ->delete();

        foreach ($profile->certifications() as $cert) {
            $certModel = EloquentCertification::find($cert->id()->value()) ?? new EloquentCertification();
            $certModel->id = $cert->id()->value();
            $certModel->skill_profile_id = $profile->id()->value();
            $certModel->name = $cert->name();
            $certModel->issuer = $cert->issuer();
            $certModel->issue_date = $cert->issueDate()->format('Y-m-d H:i:s');
            $certModel->expiry_date = $cert->expiryDate()?->format('Y-m-d H:i:s');
            $certModel->credential_url = $cert->credentialUrl();
            $certModel->verification_code = $cert->verificationCode();
            $certModel->save();
        }
    }

    public function delete(SkillProfileId $id): void
    {
        EloquentSkillProfile::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentSkillProfile $model): SkillProfile
    {
        $skills = [];
        foreach ($model->skills as $skill) {
            $skills[] = Skill::reconstitute(
                id: SkillId::of($skill->id),
                skillProfileId: SkillProfileId::of($skill->skill_profile_id),
                name: $skill->name,
                category: SkillCategory::from($skill->category),
                level: SkillLevel::from($skill->level),
                yearsOfExperience: (int) $skill->years_of_experience,
                lastUsed: new DateTimeImmutable($skill->last_used->format('Y-m-d H:i:s'))
            );
        }

        $certifications = [];
        foreach ($model->certifications as $cert) {
            $certifications[] = Certification::reconstitute(
                id: CertificationId::of($cert->id),
                skillProfileId: SkillProfileId::of($cert->skill_profile_id),
                name: $cert->name,
                issuer: $cert->issuer,
                issueDate: new DateTimeImmutable($cert->issue_date->format('Y-m-d H:i:s')),
                expiryDate: $cert->expiry_date ? new DateTimeImmutable($cert->expiry_date->format('Y-m-d H:i:s')) : null,
                credentialUrl: $cert->credential_url,
                verificationCode: $cert->verification_code
            );
        }

        return SkillProfile::reconstitute(
            id: SkillProfileId::of($model->id),
            studentId: StudentId::of($model->student_id),
            skills: $skills,
            certifications: $certifications,
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s'))
        );
    }
}
