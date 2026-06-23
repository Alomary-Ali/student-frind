<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\DTOs\AssignAcademicPlanDto;
use Modules\Academic\Application\UseCases\AssignAcademicPlan;
use Modules\Academic\Domain\Exceptions\AcademicPlanAlreadyAssignedException;
use Modules\Academic\Domain\Exceptions\CurriculumNotFoundException;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Presentation\Requests\AssignAcademicPlanRequest;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

final class AssignAcademicPlanController extends Controller
{
    public function __construct(
        private readonly AssignAcademicPlan $useCase,
    ) {}

    public function __invoke(AssignAcademicPlanRequest $request): JsonResponse
    {
        try {
            $dto = new AssignAcademicPlanDto(
                studentId: $request->input('student_id'),
                curriculumId: $request->input('curriculum_id'),
                actorUserId: (string) $request->user()?->id,
                institutionId: $request->input('institution_id'),
                estimatedGraduationDate: $request->input('estimated_graduation_date'),
            );

            $result = $this->useCase->execute($dto);

            return ApiResponse::success(
                data: $result,
                message: 'Academic plan assigned successfully.',
                status: Response::HTTP_CREATED,
            );
        } catch (StudentNotFoundException|CurriculumNotFoundException $e) {
            return ApiResponse::error('NOT_FOUND', $e->getMessage(), status: Response::HTTP_NOT_FOUND);
        } catch (AcademicPlanAlreadyAssignedException $e) {
            return ApiResponse::error('CONFLICT', $e->getMessage(), status: Response::HTTP_CONFLICT);
        }
    }
}
