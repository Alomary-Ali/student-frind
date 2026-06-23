<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Domain\Contracts\PublicPortfolioRepositoryInterface;

final readonly class IncrementPortfolioViews
{
    public function __construct(
        private PublicPortfolioRepositoryInterface $portfolios,
    ) {}

    public function execute(string $slug): void
    {
        $portfolio = $this->portfolios->findBySlug($slug);

        if ($portfolio === null) {
            return;
        }

        $portfolio->incrementViews();
        $this->portfolios->save($portfolio);
    }
}
