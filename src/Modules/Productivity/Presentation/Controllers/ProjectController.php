<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\DTOs\CreateProjectDto;
use Modules\Productivity\Application\UseCases\CreateProject;
use Modules\Productivity\Application\UseCases\UpdateProjectProgress;
use Modules\Productivity\Domain\Contracts\ProjectRepositoryInterface;
use Modules\Productivity\Presentation\Http\Requests\CreateProjectRequest;

final class ProjectController extends Controller
{
    public function __construct(
        private readonly CreateProject $createProject,
        private readonly UpdateProjectProgress $updateProjectProgress,
        private readonly ProjectRepositoryInterface $projectRepository,
    ) {}

    public function index(): View
    {
        $userId = \Modules\Shared\Domain\ValueObjects\UserId::fromString(auth()->id());
        $projects = $this->projectRepository->findByUserId($userId);

        return view('productivity.projects', compact('projects'));
    }

    public function show(string $id): View
    {
        $projectId = \Modules\Productivity\Domain\ValueObjects\ProjectId::fromString($id);
        $project = $this->projectRepository->findById($projectId);

        return view('productivity.project-detail', compact('project'));
    }

    public function store(CreateProjectRequest $request): JsonResponse
    {
        $dto = CreateProjectDto::fromRequest($request);
        $result = $this->createProject->execute($dto);

        return response()->json($result, 201);
    }

    public function updateProgress(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $result = $this->updateProjectProgress->execute(
            $id,
            $validated['progress_percentage'],
        );

        return response()->json($result);
    }
}
