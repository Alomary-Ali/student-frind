<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Application\UseCases\GetStudentAlerts;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetStudentAlertsController extends Controller
{
    public function __construct(
        private readonly GetStudentAlerts $getStudentAlerts,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $userId = (string) $request->user()->id;

        try {
            $alerts = $this->getStudentAlerts->executeUnresolved($userId);

            return ApiResponse::success(
                data: [
                    'alerts' => $alerts,
                    'count' => count($alerts),
                ],
                message: 'Alerts retrieved successfully.',
                status: Response::HTTP_OK,
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                errorCode: 'SERVER_ERROR',
                message: 'Failed to retrieve alerts.',
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }
}
