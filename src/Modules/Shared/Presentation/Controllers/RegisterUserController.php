<?php

declare(strict_types=1);

namespace Modules\Shared\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Shared\Application\DTOs\RegisterUserDto;
use Modules\Shared\Application\UseCases\RegisterUser;
use Modules\Shared\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Shared\Presentation\Requests\RegisterUserRequest;
use Modules\Shared\Presentation\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

final class RegisterUserController extends Controller
{
    public function __construct(
        private RegisterUser $registerUser,
    ) {}

    public function __invoke(RegisterUserRequest $request): JsonResponse
    {
        try {
            $dto = new RegisterUserDto(
                email: $request->input('email'),
                firstName: $request->input('first_name'),
                lastName: $request->input('last_name'),
                password: $request->input('password'),
                role: $request->input('role'),
            );

            $userDto = $this->registerUser->execute($dto);

            return (new UserResource($userDto))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);

        } catch (EmailAlreadyTakenException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => [
                    'email' => [$e->getMessage()],
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
