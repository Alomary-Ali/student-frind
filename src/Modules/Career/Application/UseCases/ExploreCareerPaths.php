<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\CareerPathDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\Entities\CareerPath;

final readonly class ExploreCareerPaths
{
    public function __construct(
        private CareerPathRepositoryInterface $repository,
        private CareerMapper $mapper,
    ) {}

    /**
     * @return list<CareerPathDto>
     */
    public function execute(?string $targetRole = null): array
    {
        if ($targetRole !== null) {
            $paths = $this->repository->findByTargetRole($targetRole);
        } else {
            $paths = $this->repository->findAll();
        }

        return array_map(
            fn (CareerPath $path) => $this->mapper->toCareerPathDto($path),
            $paths,
        );
    }
}
