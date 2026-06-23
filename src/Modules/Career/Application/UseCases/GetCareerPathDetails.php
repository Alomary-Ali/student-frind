<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\CareerPathDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\ValueObjects\CareerPathId;
use RuntimeException;

final readonly class GetCareerPathDetails
{
    public function __construct(
        private CareerPathRepositoryInterface $repository,
        private CareerMapper $mapper,
    ) {}

    public function execute(string $pathId): CareerPathDto
    {
        $id = CareerPathId::fromString($pathId);

        $path = $this->repository->findById($id);

        if ($path === null) {
            throw new RuntimeException("Career path with id [{$pathId}] not found.");
        }

        return $this->mapper->toCareerPathDto($path);
    }
}
