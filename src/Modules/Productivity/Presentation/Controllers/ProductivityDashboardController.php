<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\UseCases\GetProductivityDashboard;

final class ProductivityDashboardController extends Controller
{
    public function __construct(
        private readonly GetProductivityDashboard $getDashboard,
    ) {}

    public function __invoke(Request $request): View
    {
        $userId = (string) $request->user()->id;

        $dashboard = $this->getDashboard->execute($userId);

        return view('productivity.dashboard', compact('dashboard'));
    }
}
