<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\CreateServiceRequest;
use Modules\StudentServices\Application\UseCases\ListServiceRequests;
use Modules\StudentServices\Domain\Entities\ServiceRequest;
use Modules\StudentServices\Presentation\Http\Requests\CreateServiceRequestRequest;

final readonly class ServiceRequestApiController
{
    public function __construct(
        private CreateServiceRequest $createServiceRequest,
        private ListServiceRequests $listServiceRequests,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return response()->json(['success' => false, 'message' => 'Student profile not found'], 400);
        }

        $status = $request->input('status');
        $entities = $this->listServiceRequests->execute($studentId, $status);

        $data = array_map(fn (ServiceRequest $r): array => [
            'id' => $r->id()->value(),
            'ref_number' => $r->refNumber(),
            'category_id' => $r->categoryId(),
            'student_id' => $r->studentId(),
            'status' => $r->status()->value,
            'priority' => $r->priority()->value,
            'notes' => $r->notes(),
            'admin_notes' => $r->adminNotes(),
            'created_at' => $r->createdAt()->format('c'),
            'updated_at' => $r->updatedAt()->format('c'),
        ], $entities);

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(CreateServiceRequestRequest $request): JsonResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return response()->json(['success' => false, 'message' => 'Student profile not found'], 400);
        }

        $result = $this->createServiceRequest->execute(
            $studentId,
            $request->input('category_id'),
            $request->input('priority'),
            $request->input('notes'),
        );

        return response()->json(['success' => true, 'data' => $result], 201);
    }

    private function resolveStudentId(Request $request): ?string
    {
        $user = $request->user();
        if (! $user) {
            return null;
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();

        return $student?->id;
    }
}
