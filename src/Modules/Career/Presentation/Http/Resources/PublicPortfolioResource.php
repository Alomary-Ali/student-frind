<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Career\Application\DTOs\PublicPortfolioDto;

final class PublicPortfolioResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var PublicPortfolioDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'student_id' => $dto->studentId,
            'slug' => $dto->slug,
            'title' => $dto->title,
            'bio' => $dto->bio,
            'theme' => $dto->theme,
            'is_active' => $dto->isActive,
            'views_count' => $dto->viewsCount,
        ];
    }
}
