<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\GetAssistantSuggestions;
use Modules\StudentServices\Application\UseCases\GetConversationHistory;
use Modules\StudentServices\Application\UseCases\SendMessage;
use Modules\StudentServices\Application\UseCases\StartConversation;
use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Presentation\Http\Requests\SendMessageRequest;

final readonly class AssistantController
{
    public function __construct(
        private StartConversation $startConversation,
        private SendMessage $sendMessage,
        private GetConversationHistory $getConversationHistory,
        private GetAssistantSuggestions $getAssistantSuggestions,
        private ConversationRepositoryInterface $conversations,
    ) {}

    public function chat(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return view('student-services.assistant.chat', [
                'conversation' => null,
                'messages' => [],
            ]);
        }

        $existing = $this->conversations->findActiveByStudentId($studentId);

        if ($existing === null) {
            return view('student-services.assistant.chat', [
                'conversation' => null,
                'messages' => [],
            ]);
        }

        $history = $this->getConversationHistory->execute($existing->id()->value());

        return view('student-services.assistant.chat', [
            'conversation' => $history['conversation'] ?? null,
            'messages' => $history['messages'] ?? [],
        ]);
    }

    public function send(SendMessageRequest $request): RedirectResponse|View
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        $conversationId = $request->input('conversation_id');

        if (! $conversationId) {
            $conversation = $this->startConversation->execute($studentId);
            $conversationId = $conversation['id'];
        }

        try {
            $result = $this->sendMessage->execute(
                $conversationId,
                $studentId,
                $request->input('content'),
            );

            if ($result === null) {
                return redirect()->back()->with('error', 'المحادثة غير موجودة');
            }

            if ($request->expectsJson()) {
                return response()->json($result);
            }

            return redirect()->route('student-services.assistant.chat')
                ->with('success', 'تم إرسال الرسالة');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إرسال الرسالة');
        }
    }

    public function history(string $id, Request $request): View
    {
        $history = $this->getConversationHistory->execute($id);

        return view('student-services.assistant.history', [
            'conversation' => $history['conversation'] ?? null,
            'messages' => $history['messages'] ?? [],
        ]);
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
