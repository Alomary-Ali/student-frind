<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\PublicPortfolioDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\Gateways\CareerProfileGatewayInterface;
use Modules\Career\Domain\Contracts\PublicPortfolioRepositoryInterface;
use RuntimeException;

final readonly class GetPublicPortfolio
{
    public function __construct(
        private PublicPortfolioRepositoryInterface $portfolios,
        private CareerProfileGatewayInterface $profileGateway,
        private CareerMapper $mapper,
    ) {}

    /**
     * @return array{portfolio: PublicPortfolioDto, profile: array|null, portfolio_items: array, experiences: array}
     */
    public function execute(string $slug): array
    {
        $portfolio = $this->portfolios->findBySlug($slug);

        if ($portfolio === null || ! $portfolio->isActive()) {
            throw new RuntimeException('Portfolio is not published');
        }

        $studentId = $portfolio->studentId();
        $profile = $this->profileGateway->getProfile($studentId);
        $portfolioItems = $this->profileGateway->getPortfolioItems($studentId);
        $experiences = $this->profileGateway->getExperiences($studentId);

        $portfolio->incrementViews();
        $this->portfolios->save($portfolio);

        return [
            'portfolio' => $this->mapper->toPublicPortfolioDto($portfolio),
            'profile' => $profile,
            'portfolio_items' => $portfolioItems,
            'experiences' => $experiences,
        ];
    }
}
