<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Contracts;

use Modules\Career\Domain\Entities\PublicPortfolio;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;

interface PublicPortfolioRepositoryInterface
{
    public function findById(PublicPortfolioId $id): ?PublicPortfolio;

    public function findByStudentId(string $studentId): ?PublicPortfolio;

    public function findBySlug(string $slug): ?PublicPortfolio;

    public function save(PublicPortfolio $portfolio): void;

    public function delete(PublicPortfolioId $id): void;
}
