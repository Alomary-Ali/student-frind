<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\PublicPortfolioDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\PublicPortfolioRepositoryInterface;
use Modules\Career\Domain\Enums\PortfolioTheme;
use Modules\Career\Domain\ValueObjects\PortfolioSlug;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class PublishPortfolio
{
    public function __construct(
        private PublicPortfolioRepositoryInterface $portfolios,
        private EventDispatcherInterface $events,
        private CareerMapper $mapper,
    ) {}

    public function execute(string $studentId, ?string $slug, ?string $theme, ?string $bio, string $title): PublicPortfolioDto
    {
        $existing = $this->portfolios->findByStudentId($studentId);

        if ($existing !== null) {
            $portfolioSlug = PortfolioSlug::fromString($slug ?? $studentId);
            $portfolioTheme = $theme !== null ? PortfolioTheme::from($theme) : $existing->theme();

            $existing->updateProfile($title, $bio, $portfolioSlug);
            $existing->updateTheme($portfolioTheme);
            $existing->publish();

            $this->portfolios->save($existing);
            $this->events->dispatch($existing->releaseEvents());

            return $this->mapper->toPublicPortfolioDto($existing);
        }

        $id = PublicPortfolioId::generate();
        $portfolioSlug = PortfolioSlug::fromString($slug ?? $studentId);
        $portfolio = \Modules\Career\Domain\Entities\PublicPortfolio::create(
            id: $id,
            studentId: $studentId,
            slug: $portfolioSlug,
            title: $title,
            bio: $bio,
        );

        $portfolioTheme = PortfolioTheme::from($theme ?? 'modern');
        $portfolio->updateTheme($portfolioTheme);
        $portfolio->publish();

        $this->portfolios->save($portfolio);
        $this->events->dispatch($portfolio->releaseEvents());

        return $this->mapper->toPublicPortfolioDto($portfolio);
    }
}
