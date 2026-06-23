<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Productivity\Application\DTOs\CreateGoalDto;
use Modules\Productivity\Application\DTOs\GoalDto;
use Modules\Productivity\Application\DTOs\TaskDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Application\UseCases\CreateGoal;
use Modules\Productivity\Application\UseCases\UpdateGoalProgress;
use Modules\Productivity\Domain\Contracts\GoalRepositoryInterface;
use Modules\Productivity\Domain\Exceptions\GoalNotFoundException;
use Modules\Productivity\Domain\Exceptions\InvalidGoalProgressException;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;

final readonly class GoalController
{
    public function __construct(
        private CreateGoal $createGoal,
        private UpdateGoalProgress $updateGoalProgress,
        private GoalRepositoryInterface $goals,
        private ProductivityMapper $mapper,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_date' => 'required|date',
            'priority' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 'VALIDATION_ERROR',
                'message' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            $dto = new CreateGoalDto(
                userId: $request->input('user_id'),
                title: $request->input('title'),
                description: $request->input('description'),
                targetDate: $request->input('target_date'),
                priority: $request->input('priority'),
            );

            $goal = $this->createGoal->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Goal created successfully',
                'data' => $goal,
            ], 201);
        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'code' => 'USER_NOT_FOUND',
                'message' => $e->getMessage(),
                'errors' => [],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 'INTERNAL_ERROR',
                'message' => 'Failed to create goal',
                'errors' => [],
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $goalId = \Modules\Productivity\Domain\ValueObjects\GoalId::fromString($id);
            $goal = $this->goals->findById($goalId);

            if ($goal === null) {
                throw GoalNotFoundException::forId($id);
            }

            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $this->mapper->toGoalDto($goal),
            ]);
        } catch (GoalNotFoundException $e) {
            return response()->json([
                'success' => false,
                'code' => 'NOT_FOUND',
                'message' => $e->getMessage(),
                'errors' => [],
            ], 404);
        }
    }

    public function index(string $userId): JsonResponse
    {
        $goals = $this->goals->findByUserId($userId);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $this->mapper->toGoalDtoList($goals),
        ]);
    }

    public function updateProgress(string $id): JsonResponse
    {
        try {
            $progress = (float) request()->input('progress');
            $goal = $this->updateGoalProgress->execute($id, $progress);

            return response()->json([
                'success' => true,
                'message' => 'Goal progress updated successfully',
                'data' => $goal,
            ]);
        } catch (GoalNotFoundException $e) {
            return response()->json([
                'success' => false,
                'code' => 'NOT_FOUND',
                'message' => $e->getMessage(),
                'errors' => [],
            ], 404);
        } catch (InvalidGoalProgressException $e) {
            return response()->json([
                'success' => false,
                'code' => 'VALIDATION_ERROR',
                'message' => $e->getMessage(),
                'errors' => [],
            ], 422);
        }
    }
}
