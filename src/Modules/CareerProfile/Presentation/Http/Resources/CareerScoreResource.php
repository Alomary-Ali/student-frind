<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CareerProfile\Application\DTOs\CareerScoreDto;

/**
 * @property-read CareerScoreDto $resource
 */
final class CareerScoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'score' => $this->resource->score,
            'breakdown' => $this->resource->breakdown,
        ];
    }
}
