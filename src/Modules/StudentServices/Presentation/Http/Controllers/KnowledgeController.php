<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\StudentServices\Application\UseCases\CreateKnowledgeArticle;
use Modules\StudentServices\Application\UseCases\SearchKnowledgeBase;
use Modules\StudentServices\Application\UseCases\UpdateKnowledgeArticle;
use Modules\StudentServices\Domain\Contracts\KnowledgeRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\KnowledgeArticleId;

final readonly class KnowledgeController
{
    public function __construct(
        private SearchKnowledgeBase $searchKnowledgeBase,
        private CreateKnowledgeArticle $createKnowledgeArticle,
        private UpdateKnowledgeArticle $updateKnowledgeArticle,
        private KnowledgeRepositoryInterface $articles,
    ) {}

    public function index(Request $request): View
    {
        $query = $request->input('q');
        $articles = $query
            ? $this->searchKnowledgeBase->execute($query)
            : [];

        return view('student-services.knowledge.index', [
            'articles' => $articles,
            'query' => $query,
        ]);
    }

    public function show(string $id): View
    {
        $articleId = KnowledgeArticleId::fromString($id);
        $article = $this->articles->findArticleById($articleId);

        return view('student-services.knowledge.show', ['article' => $article]);
    }

    public function search(Request $request): View
    {
        $query = $request->input('q', '');
        $articles = $query ? $this->searchKnowledgeBase->execute($query) : [];

        return view('student-services.knowledge.search', [
            'articles' => $articles,
            'query' => $query,
        ]);
    }
}
