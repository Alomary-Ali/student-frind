<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Queries\GetGraduationProgress;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetGraduationProgressController extends Controller
{
    public function __construct(
        private readonly GetGraduationProgress $query,
    ) {}

    public function __invoke(string $studentId): JsonResponse
    {
        $progress = $this->query->execute($studentId);

        if ($progress === null) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Graduation progress not found for student.',
                status: Response::HTTP_NOT_FOUND,
            );
        }

        return ApiResponse::success(data: $progress);
    }
}
