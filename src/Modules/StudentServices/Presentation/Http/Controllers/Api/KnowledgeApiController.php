<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\SearchKnowledgeBase;
use Modules\StudentServices\Domain\Contracts\KnowledgeRepositoryInterface;

final readonly class KnowledgeApiController
{
    public function __construct(
        private SearchKnowledgeBase $searchKnowledgeBase,
        private KnowledgeRepositoryInterface $articles,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = $request->input('query');

        if ($query) {
            $results = $this->searchKnowledgeBase->execute($query);
        } else {
            $results = $this->articles->findAllPublished();
        }

        $data = array_map(fn ($a): array => [
            'id' => $a->id()->value(),
            'category_id' => $a->categoryId(),
            'title' => $a->title(),
            'slug' => $a->slug(),
            'content' => $a->content(),
            'tags' => $a->tags(),
            'status' => $a->status()->value,
            'view_count' => $a->viewCount(),
            'created_at' => $a->createdAt()->format('c'),
        ], $results);

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query', '');

        if (empty($query)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = $this->searchKnowledgeBase->execute($query);

        $data = array_map(fn ($a): array => [
            'id' => $a->id()->value(),
            'category_id' => $a->categoryId(),
            'title' => $a->title(),
            'slug' => $a->slug(),
            'content' => $a->content(),
            'tags' => $a->tags(),
            'status' => $a->status()->value,
            'view_count' => $a->viewCount(),
            'created_at' => $a->createdAt()->format('c'),
        ], $results);

        return response()->json(['success' => true, 'data' => $data]);
    }

    private function resolveStudentId(Request $request): ?string
    {
        $user = $request->user();
        if (! $user) {
            return null;
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();

        return $student?->id;
    }
}
