<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\CalendarEventRepositoryInterface;

final class ProductivityCalendarController extends Controller
{
    public function __construct(
        private readonly CalendarEventRepositoryInterface $events,
        private readonly ProductivityMapper $mapper,
    ) {}

    public function index(Request $request): View
    {
        $userId = (string) $request->user()->id;
        $eventEntities = $this->events->findByUserId($userId);
        $events = collect($this->mapper->toCalendarEventDtoList($eventEntities));

        return view('productivity.calendar', compact('events'));
    }
}
