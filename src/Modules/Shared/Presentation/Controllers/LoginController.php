<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Shared\Application\UseCases\AuthenticateUser;
use Modules\Shared\Domain\Exceptions\AccountLockedException;
use Modules\Shared\Domain\Exceptions\InvalidCredentialsException;
use Modules\Shared\Domain\Exceptions\UserSuspendedException;
use Modules\Shared\Presentation\Requests\LoginRequest;

final class LoginController extends Controller
{
    public function __construct(
        private AuthenticateUser $authenticateUser,
    ) {}

    public function __invoke(LoginRequest $request): RedirectResponse
    {
        try {
            $this->authenticateUser->execute(
                $request->input('academic_id'),
                $request->input('password'),
            );

            $request->session()->regenerate();

            return redirect()->route('academic.dashboard');
        } catch (AccountLockedException $e) {
            return back()->withErrors([
                'academic_id' => $e->getMessage(),
            ]);
        } catch (InvalidCredentialsException $e) {
            return back()->withErrors([
                'academic_id' => 'الرقم الأكاديمي أو كلمة المرور غير صحيحة',
            ]);
        } catch (UserSuspendedException $e) {
            return back()->withErrors([
                'academic_id' => 'تم تعليق حسابك. يرجى التواصل مع الإدارة',
            ]);
        }
    }
}
