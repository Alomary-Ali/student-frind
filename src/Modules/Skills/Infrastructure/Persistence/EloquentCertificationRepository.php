<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence;

use DateTimeImmutable;
use Modules\Skills\Domain\Contracts\CertificationRepositoryInterface;
use Modules\Skills\Domain\Entities\Certification;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentCertification;

final class EloquentCertificationRepository implements CertificationRepositoryInterface
{
    public function findById(CertificationId $id): ?Certification
    {
        $model = EloquentCertification::find($id->value());

        if ($model === null) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function save(Certification $certification): void
    {
        $model = EloquentCertification::find($certification->id()->value());

        if ($model === null) {
            $model = new EloquentCertification;
            $model->id = $certification->id()->value();
        }

        $model->skill_profile_id = $certification->skillProfileId()->value();
        $model->name = $certification->name();
        $model->issuer = $certification->issuer();
        $model->issue_date = $certification->issueDate()->format('Y-m-d H:i:s');
        $model->expiry_date = $certification->expiryDate()?->format('Y-m-d H:i:s');
        $model->credential_url = $certification->credentialUrl();
        $model->verification_code = $certification->verificationCode();
        $model->save();
    }

    public function delete(CertificationId $id): void
    {
        EloquentCertification::where('id', $id->value())->delete();
    }

    private function toEntity(EloquentCertification $model): Certification
    {
        return Certification::reconstitute(
            id: CertificationId::of($model->id),
            skillProfileId: SkillProfileId::of($model->skill_profile_id),
            name: $model->name,
            issuer: $model->issuer,
            issueDate: new DateTimeImmutable($model->issue_date->format('Y-m-d H:i:s')),
            expiryDate: $model->expiry_date ? new DateTimeImmutable($model->expiry_date->format('Y-m-d H:i:s')) : null,
            credentialUrl: $model->credential_url,
            verificationCode: $model->verification_code,
        );
    }
}
