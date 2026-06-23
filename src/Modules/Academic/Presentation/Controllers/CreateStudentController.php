<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\DTOs\CreateStudentDto;
use Modules\Academic\Application\UseCases\CreateStudent;
use Modules\Academic\Domain\Exceptions\StudentAlreadyExistsException;
use Modules\Academic\Presentation\Requests\CreateStudentRequest;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;
use Symfony\Component\HttpFoundation\Response;

final class CreateStudentController extends Controller
{
    public function __construct(
        private readonly CreateStudent $useCase,
    ) {}

    public function __invoke(CreateStudentRequest $request): JsonResponse
    {
        try {
            $dto = new CreateStudentDto(
                userId: $request->input('user_id'),
                studentNumber: $request->input('student_number'),
                institutionId: $request->input('institution_id'),
            );

            $result = $this->useCase->execute($dto);

            return ApiResponse::success(
                data: [
                    'id' => $result->id,
                    'user_id' => $result->userId,
                    'student_number' => $result->studentNumber,
                    'academic_status' => $result->academicStatus,
                    'academic_standing' => $result->academicStanding,
                    'cumulative_gpa' => $result->cumulativeGpa,
                ],
                message: 'Student academic profile created successfully.',
                status: Response::HTTP_CREATED,
            );
        } catch (StudentAlreadyExistsException|UserNotFoundException $e) {
            return ApiResponse::error('CONFLICT', $e->getMessage(), status: Response::HTTP_CONFLICT);
        }
    }
}
