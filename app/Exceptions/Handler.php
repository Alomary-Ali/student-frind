<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Shared\Domain\Exceptions\AccountLockedException;
use Modules\Shared\Domain\Exceptions\InvalidCredentialsException;
use Modules\Shared\Domain\Exceptions\UserSuspendedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (AccountLockedException $e, Request $request) {
            return $this->errorResponse(
                message: $e->getMessage(),
                code: 'ACCOUNT_LOCKED',
                statusCode: 423,
                request: $request,
            );
        });

        $this->renderable(function (InvalidCredentialsException $e, Request $request) {
            return $this->errorResponse(
                message: 'الرقم الأكاديمي أو كلمة المرور غير صحيحة',
                code: 'INVALID_CREDENTIALS',
                statusCode: 401,
                request: $request,
            );
        });

        $this->renderable(function (UserSuspendedException $e, Request $request) {
            return $this->errorResponse(
                message: 'تم تعليق حسابك. يرجى التواصل مع الإدارة',
                code: 'ACCOUNT_SUSPENDED',
                statusCode: 403,
                request: $request,
            );
        });

        $this->renderable(function (HttpException $e, Request $request) {
            return $this->errorResponse(
                message: $e->getMessage() ?: 'حدث خطأ في الطلب',
                code: 'HTTP_ERROR',
                statusCode: $e->getStatusCode(),
                request: $request,
            );
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): Response
    {
        // Log the exception
        $this->logException($e, $request);

        return parent::render($request, $e);
    }

    /**
     * Create a standardized error response.
     */
    private function errorResponse(
        string $message,
        string $code,
        int $statusCode,
        Request $request,
    ): JsonResponse|Response {
        $response = [
            'success' => false,
            'data' => null,
            'message' => $message,
            'error' => [
                'code' => $code,
            ],
            'meta' => null,
        ];

        if ($request->expectsJson()) {
            return response()->json($response, $statusCode);
        }

        // For web requests, return a view with the error
        return response()->view('errors.general', [
            'message' => $message,
            'code' => $code,
        ], $statusCode);
    }

    /**
     * Log the exception with context.
     */
    private function logException(Throwable $e, Request $request): void
    {
        $context = [
            'url' => $request->url(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];

        logger()->error('Exception occurred', $context);
    }
}
