<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shared\Application\UseCases\VerifyUserEmail;
use Modules\Shared\Domain\Exceptions\UserNotFoundException;
use Modules\Shared\Presentation\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

final class VerifyEmailController extends Controller
{
    public function __construct(
        private VerifyUserEmail $verifyUserEmail,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'string', 'uuid'],
        ]);

        try {
            $userDto = $this->verifyUserEmail->execute(
                $request->input('user_id')
            );

            return (new UserResource($userDto))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
