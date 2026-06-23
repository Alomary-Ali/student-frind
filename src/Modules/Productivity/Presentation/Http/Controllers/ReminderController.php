<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Productivity\Application\DTOs\CreateReminderDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Application\UseCases\CreateReminder;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;

final readonly class ReminderController
{
    public function __construct(
        private CreateReminder $createReminder,
        private ReminderRepositoryInterface $reminders,
        private ProductivityMapper $mapper,
    ) {}

    public function store(): JsonResponse
    {
        try {
            $dto = new CreateReminderDto(
                userId: request()->input('user_id'),
                message: request()->input('message'),
                triggerAt: request()->input('trigger_at'),
                type: request()->input('type'),
                linkedTaskId: request()->input('linked_task_id'),
            );

            $reminder = $this->createReminder->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Reminder created successfully',
                'data' => $reminder,
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
                'message' => 'Failed to create reminder',
                'errors' => [],
            ], 500);
        }
    }

    public function index(string $userId): JsonResponse
    {
        $reminders = $this->reminders->findByUserId($userId);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $this->mapper->toReminderDtoList($reminders),
        ]);
    }
}
