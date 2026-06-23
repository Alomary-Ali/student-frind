<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\Mappers\ProductivityMapper;
use Modules\Productivity\Domain\Contracts\ReminderRepositoryInterface;

final class ProductivityReminderController extends Controller
{
    public function __construct(
        private readonly ReminderRepositoryInterface $reminders,
        private readonly ProductivityMapper $mapper,
    ) {}

    public function index(Request $request): View
    {
        $userId = (string) $request->user()->id;
        $reminderEntities = $this->reminders->findByUserId($userId);
        $reminders = collect($this->mapper->toReminderDtoList($reminderEntities));

        return view('productivity.reminders', compact('reminders'));
    }
}
