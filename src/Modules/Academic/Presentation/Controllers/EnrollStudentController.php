<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\DTOs\EnrollStudentDto;
use Modules\Academic\Application\UseCases\EnrollStudentInCourse;
use Modules\Academic\Domain\Exceptions\CourseNotFoundException;
use Modules\Academic\Domain\Exceptions\DuplicateEnrollmentException;
use Modules\Academic\Domain\Exceptions\PrerequisiteNotMetException;
use Modules\Academic\Domain\Exceptions\SemesterNotFoundException;
use Modules\Academic\Domain\Exceptions\StudentNotEligibleException;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;
use Modules\Academic\Presentation\Requests\EnrollStudentRequest;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

final class EnrollStudentController extends Controller
{
    public function __construct(
        private readonly EnrollStudentInCourse $useCase,
    ) {}

    public function __invoke(EnrollStudentRequest $request): JsonResponse
    {
        try {
            $dto = new EnrollStudentDto(
                studentId: $request->input('student_id'),
                courseId: $request->input('course_id'),
                semesterId: $request->input('semester_id'),
                actorUserId: (string) $request->user()?->id,
            );

            $result = $this->useCase->execute($dto);

            return ApiResponse::success(
                data: [
                    'id' => $result->id,
                    'student_id' => $result->studentId,
                    'course_id' => $result->courseId,
                    'semester_id' => $result->semesterId,
                    'status' => $result->status,
                ],
                message: 'Student enrolled successfully.',
                status: Response::HTTP_CREATED,
            );
        } catch (StudentNotFoundException|CourseNotFoundException|SemesterNotFoundException $e) {
            return ApiResponse::error('NOT_FOUND', $e->getMessage(), status: Response::HTTP_NOT_FOUND);
        } catch (PrerequisiteNotMetException $e) {
            return ApiResponse::error(
                'PREREQUISITE_NOT_MET',
                'Prerequisites not met: ' . $e->getMessage(),
                status: Response::HTTP_CONFLICT
            );
        } catch (DuplicateEnrollmentException|StudentNotEligibleException $e) {
            return ApiResponse::error('CONFLICT', $e->getMessage(), status: Response::HTTP_CONFLICT);
        }
    }
}
