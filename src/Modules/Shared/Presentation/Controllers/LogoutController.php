<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class LogoutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $this->logoutCurrentDevice($request);

        return redirect()->route('login');
    }

    public function logoutAllDevices(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user === null) {
            return redirect()->route('login');
        }

        // Revoke all API tokens
        $user->tokens()->delete();

        // Invalidate all sessions for this user
        if (config('session.driver') === 'database') {
            \Modules\Shared\Infrastructure\Persistence\EloquentSession::where('user_id', $user->id)
                ->delete();
        }

        // Logout current session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'تم تسجيل الخروج من جميع الأجهزة بنجاح');
    }

    private function logoutCurrentDevice(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
