<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class EmploymentReadinessResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'score' => $this->resource['score'] ?? 0,
            'breakdown' => $this->resource['breakdown'] ?? [],
            'level' => $this->getLevel($this->resource['score'] ?? 0),
        ];
    }

    private function getLevel(float $score): string
    {
        return match (true) {
            $score >= 80 => 'excellent',
            $score >= 60 => 'good',
            $score >= 40 => 'developing',
            default => 'beginner',
        };
    }
}
