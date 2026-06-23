<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\GetStudentServicesDashboard;

final readonly class DashboardController
{
    public function __construct(
        private GetStudentServicesDashboard $getDashboard,
    ) {}

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);

        $dashboard = $studentId
            ? $this->getDashboard->execute($studentId)
            : [
                'active_requests_count' => 0,
                'pending_documents_count' => 0,
                'unread_notifications' => 0,
                'recent_requests' => [],
                'recent_conversation' => null,
                'available_services' => [],
            ];

        return view('student-services.dashboard', ['dashboard' => $dashboard]);
    }

    private function resolveStudentId(Request $request): string
    {
        $user = $request->user();

        if ($user === null) {
            throw new \RuntimeException('User not authenticated');
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();

        if ($student === null) {
            throw new \RuntimeException('Student profile not found');
        }

        return $student->id;
    }
}
