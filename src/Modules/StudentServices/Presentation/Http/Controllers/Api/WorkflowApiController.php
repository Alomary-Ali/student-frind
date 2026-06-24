<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\DefineWorkflow;
use Modules\StudentServices\Application\UseCases\GetWorkflowStatus;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;
use Modules\StudentServices\Infrastructure\Persistence\EloquentServiceWorkflow;

final readonly class WorkflowApiController
{
    public function __construct(
        private DefineWorkflow $defineWorkflow,
        private GetWorkflowStatus $getWorkflowStatus,
        private ServiceRequestRepositoryInterface $requests,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $result = $this->defineWorkflow->execute(
            $request->input('service_category_id'),
            $request->input('name'),
            $request->input('steps', []),
        );

        return response()->json(['success' => true, 'data' => $result], 201);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $workflow = EloquentServiceWorkflow::find($id);

        if ($workflow === null) {
            return response()->json(['success' => false, 'message' => 'Workflow not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $workflow->id,
                'service_category_id' => $workflow->service_category_id,
                'name' => $workflow->name,
                'status' => $workflow->status,
                'created_at' => $workflow->created_at?->format('c'),
                'updated_at' => $workflow->updated_at?->format('c'),
            ],
        ]);
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
