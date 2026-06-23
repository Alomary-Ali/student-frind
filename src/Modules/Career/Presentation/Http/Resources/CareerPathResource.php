<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Career\Application\DTOs\CareerPathDto;

final class CareerPathResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var CareerPathDto $dto */
        $dto = $this->resource;

        return [
            'id' => $dto->id,
            'title' => $dto->title,
            'description' => $dto->description,
            'target_role' => $dto->targetRole,
            'required_skills' => $dto->requiredSkills,
            'stages' => $dto->stages,
            'average_salary' => $dto->averageSalary,
            'growth_rate' => $dto->growthRate,
            'total_duration' => $dto->totalDuration,
        ];
    }
}
