<?php

declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;

final readonly class GetServiceStats
{
    public function __construct(
        private ServiceRequestRepositoryInterface $requests,
    ) {}

    public function execute(?string $studentId = null): array
    {
        if ($studentId !== null) {
            $all = $this->requests->findByStudentId($studentId);
        } else {
            $all = [];
        }

        $total = count($all);

        $byStatus = [];
        $byCategory = [];

        foreach ($all as $request) {
            $s = $request->status()->value;
            $byStatus[$s] = ($byStatus[$s] ?? 0) + 1;

            $c = $request->categoryId();
            $byCategory[$c] = ($byCategory[$c] ?? 0) + 1;
        }

        return [
            'total_requests' => $total,
            'by_status' => $byStatus,
            'by_category' => $byCategory,
        ];
    }
}
