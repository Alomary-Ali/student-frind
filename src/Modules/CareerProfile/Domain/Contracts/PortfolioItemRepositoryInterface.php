<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Contracts;

use Modules\CareerProfile\Domain\Entities\PortfolioItem;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;

interface PortfolioItemRepositoryInterface
{
    public function findById(PortfolioItemId $id): ?PortfolioItem;

    public function save(PortfolioItem $item): void;

    public function delete(PortfolioItemId $id): void;
}
