<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\DTOs\CreateAssignmentDto;
use Modules\Productivity\Application\UseCases\CreateAssignment;
use Modules\Productivity\Application\UseCases\UpdateAssignmentProgress;
use Modules\Productivity\Domain\Contracts\AssignmentRepositoryInterface;
use Modules\Productivity\Presentation\Http\Requests\CreateAssignmentRequest;

final class AssignmentController extends Controller
{
    public function __construct(
        private readonly CreateAssignment $createAssignment,
        private readonly UpdateAssignmentProgress $updateAssignmentProgress,
        private readonly AssignmentRepositoryInterface $assignmentRepository,
    ) {}

    public function index(): View
    {
        $userId = \Modules\Shared\Domain\ValueObjects\UserId::fromString(auth()->id());
        $assignments = $this->assignmentRepository->findByUserId($userId);

        return view('productivity.assignments', compact('assignments'));
    }

    public function show(string $id): View
    {
        $assignmentId = \Modules\Productivity\Domain\ValueObjects\AssignmentId::fromString($id);
        $assignment = $this->assignmentRepository->findById($assignmentId);

        return view('productivity.assignment-detail', compact('assignment'));
    }

    public function store(CreateAssignmentRequest $request): JsonResponse
    {
        $dto = CreateAssignmentDto::fromRequest($request);
        $result = $this->createAssignment->execute($dto);

        return response()->json($result, 201);
    }

    public function updateProgress(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:in_progress,submitted,late',
            'submission_url' => 'nullable|string',
        ]);

        $result = $this->updateAssignmentProgress->execute(
            $id,
            $validated['status'],
            $validated['submission_url'] ?? null,
        );

        return response()->json($result);
    }
}
