<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Productivity\Application\DTOs\CreateCalendarEventDto;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Application\UseCases\CreateCalendarEvent;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;

final readonly class CalendarEventController
{
    public function __construct(
        private CreateCalendarEvent $createCalendarEvent,
        private CalendarEventRepositoryInterface $events,
        private ProductivityMapper $mapper,
    ) {}

    public function store(): JsonResponse
    {
        try {
            $dto = new CreateCalendarEventDto(
                userId: request()->input('user_id'),
                title: request()->input('title'),
                description: request()->input('description'),
                startsAt: request()->input('starts_at'),
                endsAt: request()->input('ends_at'),
                isAllDay: (bool) request()->input('is_all_day', false),
                linkedTaskId: request()->input('linked_task_id'),
            );

            $event = $this->createCalendarEvent->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Calendar event created successfully',
                'data' => $event,
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
                'message' => 'Failed to create calendar event',
                'errors' => [],
            ], 500);
        }
    }

    public function index(string $userId): JsonResponse
    {
        $events = $this->events->findByUserId($userId);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $this->mapper->toCalendarEventDtoList($events),
        ]);
    }
}
