<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\ValueObjects\GoalId;

final class ProductivityGoalController extends Controller
{
    public function __construct(
        private readonly GoalRepositoryInterface $goals,
        private readonly ProductivityMapper $mapper,
    ) {}

    public function index(Request $request): View
    {
        $userId = (string) $request->user()->id;
        $goalEntities = $this->goals->findByUserId($userId);
        $goals = collect($this->mapper->toGoalDtoList($goalEntities));

        return view('productivity.goals', compact('goals'));
    }

    public function show(Request $request, string $id): View
    {
        $goalId = GoalId::fromString($id);
        $goal = $this->goals->findById($goalId);

        return view('productivity.goals-show', ['id' => $id, 'goal' => $goal]);
    }
}
