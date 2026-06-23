<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Productivity\Application\DTOs\ProductivityDashboardDto;
use Modules\Productivity\Application\DTOs\ProductivitySnapshotDto;
use Modules\Productivity\Application\UseCases\GenerateProductivitySnapshot;
use Modules\Productivity\Application\UseCases\GetProductivityDashboard;

final readonly class ProductivityDashboardController
{
    public function __construct(
        private GetProductivityDashboard $getDashboard,
        private GenerateProductivitySnapshot $generateSnapshot,
    ) {}

    public function show(string $userId): JsonResponse
    {
        $dashboard = $this->getDashboard->execute($userId);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $dashboard,
        ]);
    }

    public function generateSnapshot(string $userId): JsonResponse
    {
        try {
            $snapshotDate = request()->input('snapshot_date', now()->format('Y-m-d'));
            $snapshot = $this->generateSnapshot->execute($userId, $snapshotDate);

            return response()->json([
                'success' => true,
                'message' => 'Productivity snapshot generated successfully',
                'data' => $snapshot,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 'INTERNAL_ERROR',
                'message' => 'Failed to generate snapshot',
                'errors' => [],
            ], 500);
        }
    }
}
