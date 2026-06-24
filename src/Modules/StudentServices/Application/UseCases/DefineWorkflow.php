<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Entities\ServiceWorkflow;

final readonly class DefineWorkflow
{
    public function __construct() {}

    public function execute(string $serviceCategoryId, string $name, array $steps = []): array
    {
        $workflow = ServiceWorkflow::create(
            serviceCategoryId: $serviceCategoryId,
            name: $name,
            steps: $steps,
        );

        return [
            'id' => $workflow->id(),
            'service_category_id' => $workflow->serviceCategoryId(),
            'name' => $workflow->name(),
            'status' => $workflow->status()->value,
            'steps' => $workflow->steps(),
            'created_at' => $workflow->createdAt()->format('c'),
        ];
    }
}
