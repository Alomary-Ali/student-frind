<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

final class AuthPageController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function showResetPassword(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function showUnauthorized(): View
    {
        return view('auth.unauthorized');
    }
}
