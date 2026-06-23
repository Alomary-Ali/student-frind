<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\DTOs\RecordGradeDto;
use Modules\Academic\Application\UseCases\RecordAcademicGrade;
use Modules\Academic\Domain\Exceptions\EnrollmentNotFoundException;
use Modules\Academic\Presentation\Requests\RecordGradeRequest;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

final class RecordGradeController extends Controller
{
    public function __construct(
        private readonly RecordAcademicGrade $useCase,
    ) {}

    public function __invoke(RecordGradeRequest $request): JsonResponse
    {
        try {
            $dto = new RecordGradeDto(
                enrollmentId: $request->input('enrollment_id'),
                gradeLetter: $request->input('grade'),
                recordedByUserId: (string) $request->user()?->id,
            );

            $result = $this->useCase->execute($dto);

            return ApiResponse::success(
                data: $result,
                message: 'Grade recorded successfully.',
                status: Response::HTTP_CREATED,
            );
        } catch (EnrollmentNotFoundException $e) {
            return ApiResponse::error('NOT_FOUND', $e->getMessage(), status: Response::HTTP_NOT_FOUND);
        }
    }
}
