<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Application\DTOs\CreateCourseDto;
use Modules\Academic\Application\UseCases\CreateCourse;
use Modules\Academic\Presentation\Requests\CreateCourseRequest;
use Modules\Academic\Presentation\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateCourseController extends Controller
{
    public function __construct(
        private readonly CreateCourse $useCase,
    ) {}

    public function __invoke(CreateCourseRequest $request): JsonResponse
    {
        $dto = new CreateCourseDto(
            code: $request->input('code'),
            title: $request->input('title'),
            description: $request->input('description', ''),
            creditHours: (int) $request->input('credit_hours'),
            institutionId: $request->input('institution_id'),
        );

        $actorId = (string) $request->user()?->id;
        $result = $this->useCase->execute($dto, $actorId);

        return ApiResponse::success(
            data: [
                'id' => $result->id,
                'code' => $result->code,
                'title' => $result->title,
                'credit_hours' => $result->creditHours,
            ],
            message: 'Course created successfully.',
            status: Response::HTTP_CREATED,
        );
    }
}
