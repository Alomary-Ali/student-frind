<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Application\UseCases\CompleteTask;
use Modules\Productivity\Domain\Contracts\TaskRepositoryInterface;
use Modules\Productivity\Domain\ValueObjects\TaskId;

final class ProductivityTaskController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
        private readonly ProductivityMapper $mapper,
        private readonly CompleteTask $completeTask,
    ) {}

    public function complete(Request $request, string $id): RedirectResponse
    {
        $this->completeTask->execute($id);

        return redirect()->route('productivity.tasks');
    }

    public function index(Request $request): View
    {
        $userId = (string) $request->user()->id;
        $taskEntities = $this->tasks->findByUserId($userId);
        $tasks = collect($this->mapper->toTaskDtoList($taskEntities));

        return view('productivity.tasks', compact('tasks'));
    }

    public function show(Request $request, string $id): View
    {
        $taskId = TaskId::fromString($id);
        $task = $this->tasks->findById($taskId);

        return view('productivity.tasks-show', compact('task'));
    }
}
