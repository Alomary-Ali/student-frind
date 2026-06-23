<?php

declare(strict_types=1);

namespace Modules\Notifications\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Notifications\Application\UseCases\GetStudentNotifications;
use Modules\Notifications\Application\UseCases\MarkNotificationAsRead;

final readonly class NotificationController
{
    public function __construct(
        private GetStudentNotifications $getStudentNotifications,
        private MarkNotificationAsRead $markNotificationAsRead,
    ) {}

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $notifications = $studentId ? $this->getStudentNotifications->execute($studentId) : [];

        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function markAsRead(string $id, Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        $this->markNotificationAsRead->execute($id, $studentId);

        return redirect()->back()->with('success', 'تم تحديث الإشعار');
    }

    private function resolveStudentId(Request $request): ?string
    {
        $user = $request->user();
        if (! $user) {
            return null;
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();

        return $student?->id;
    }
}
