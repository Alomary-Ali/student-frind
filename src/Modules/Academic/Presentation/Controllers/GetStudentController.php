<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\Queries\GetStudentAcademicProfile;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

final class GetStudentController extends Controller
{
    public function __construct(
        private readonly GetStudentAcademicProfile $query,
    ) {}

    public function __invoke(string $studentId): JsonResponse
    {
        $profile = $this->query->execute($studentId);

        if ($profile === null) {
            return ApiResponse::error(
                'NOT_FOUND',
                'Student not found.',
                status: Response::HTTP_NOT_FOUND,
            );
        }

        return ApiResponse::success(data: $profile);
    }
}
