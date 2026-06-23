<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Responses;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    /**
     * @param  array<string, mixed>|null  $data
     */
    public static function success(
        mixed $data = null,
        string $message = '',
        int $status = 200,
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * @param  array<string, mixed>|null  $errors
     */
    public static function error(
        string $code,
        string $message,
        ?array $errors = null,
        int $status = 400,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'errors' => $errors ?? (object) [],
        ], $status);
    }
}
