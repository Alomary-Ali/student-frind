<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\StudentServices\Application\DTOs\KnowledgeArticleDto;

final class KnowledgeArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var KnowledgeArticleDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'category_id' => $dto->categoryId,
            'title' => $dto->title,
            'slug' => $dto->slug,
            'content' => $dto->content,
            'tags' => $dto->tags,
            'status' => $dto->status,
            'view_count' => $dto->viewCount,
            'created_at' => $dto->createdAt,
        ];
    }
}
