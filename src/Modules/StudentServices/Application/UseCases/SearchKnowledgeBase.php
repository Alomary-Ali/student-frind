<?php
declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\KnowledgeRepositoryInterface;

final readonly class SearchKnowledgeBase
{
    public function __construct(
        private KnowledgeRepositoryInterface $articles,
    ) {}

    public function execute(string $query): array
    {
        return $this->articles->searchArticles($query);
    }
}
