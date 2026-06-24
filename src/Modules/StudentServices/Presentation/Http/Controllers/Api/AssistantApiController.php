<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\GetConversationHistory;
use Modules\StudentServices\Application\UseCases\SendMessage;
use Modules\StudentServices\Application\UseCases\StartConversation;
use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;

final readonly class AssistantApiController
{
    public function __construct(
        private StartConversation $startConversation,
        private SendMessage $sendMessage,
        private GetConversationHistory $getConversationHistory,
        private ConversationRepositoryInterface $conversations,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return response()->json(['success' => false, 'message' => 'Student profile not found'], 400);
        }

        $entities = $this->conversations->findByStudentId($studentId);

        $data = array_map(fn ($c): array => [
            'id' => $c->id()->value(),
            'student_id' => $c->studentId(),
            'title' => $c->title(),
            'status' => $c->status()->value,
            'created_at' => $c->createdAt()->format('c'),
            'updated_at' => $c->updatedAt()->format('c'),
        ], $entities);

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function start(Request $request): JsonResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return response()->json(['success' => false, 'message' => 'Student profile not found'], 400);
        }

        $result = $this->startConversation->execute(
            $studentId,
            $request->input('title'),
        );

        return response()->json(['success' => true, 'data' => $result], 201);
    }

    public function send(Request $request, string $id): JsonResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return response()->json(['success' => false, 'message' => 'Student profile not found'], 400);
        }

        $result = $this->sendMessage->execute(
            $id,
            $studentId,
            $request->input('content'),
        );

        if ($result === null) {
            return response()->json(['success' => false, 'message' => 'Conversation not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $result], 201);
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
