<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Contracts;

use Modules\Skills\Domain\Entities\Certification;
use Modules\Skills\Domain\ValueObjects\CertificationId;

interface CertificationRepositoryInterface
{
    public function findById(CertificationId $id): ?Certification;

    public function save(Certification $certification): void;

    public function delete(CertificationId $id): void;
}
