<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Application\UseCases\CreateSemesterPlan;
use Modules\Academic\Presentation\Responses\ApiResponse;

final class CreateSemesterPlanController extends Controller
{
    public function __construct(
        private readonly CreateSemesterPlan $useCase,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|uuid',
            'semester_id' => 'required|uuid',
            'course_ids' => 'required|array',
            'course_ids.*' => 'uuid',
            'notes' => 'nullable|string',
        ]);

        $plan = $this->useCase->execute(
            studentId: $validated['student_id'],
            semesterId: $validated['semester_id'],
            courseIds: $validated['course_ids'],
            notes: $validated['notes'] ?? null,
        );

        return ApiResponse::success(
            data: [
                'id' => $plan->id()->value(),
                'student_id' => $plan->studentId()->value(),
                'semester_id' => $plan->semesterId()->value(),
                'planned_courses' => $plan->plannedCourses(),
                'total_credits' => $plan->totalCredits(),
                'status' => $plan->status(),
                'notes' => $plan->notes(),
            ],
            message: 'Semester plan created successfully',
        )->setStatusCode(201);
    }
}
