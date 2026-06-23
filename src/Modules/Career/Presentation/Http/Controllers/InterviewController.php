<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Career\Application\UseCases\GetInterviewFeedback;
use Modules\Career\Application\UseCases\GetInterviewHistory;
use Modules\Career\Application\UseCases\GetInterviewQuestions;
use Modules\Career\Application\UseCases\ScheduleInterview;
use Modules\Career\Application\UseCases\SubmitInterviewAttempt;
use Modules\Career\Presentation\Http\Requests\ScheduleInterviewRequest;

final readonly class InterviewController
{
    public function __construct(
        private ScheduleInterview $scheduleInterview,
        private GetInterviewQuestions $getInterviewQuestions,
        private SubmitInterviewAttempt $submitInterviewAttempt,
        private GetInterviewFeedback $getInterviewFeedback,
        private GetInterviewHistory $getInterviewHistory,
    ) {}

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $history = $studentId ? $this->getInterviewHistory->execute($studentId) : [];

        return view('career.interviews.index', ['history' => $history]);
    }

    public function schedule(ScheduleInterviewRequest $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $this->scheduleInterview->execute($studentId, $request->all());

            return redirect()->back()->with('success', 'تم جدولة المقابلة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء جدولة المقابلة')->withInput();
        }
    }

    public function show(string $id, Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $feedback = $studentId ? $this->getInterviewFeedback->execute($studentId, $id) : null;

        return view('career.interviews.show', ['feedback' => $feedback]);
    }

    public function submit(string $id, Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $this->submitInterviewAttempt->execute($studentId, $id, $request->all());

            return redirect()->back()->with('success', 'تم تقديم المحاولة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تقديم المحاولة')->withInput();
        }
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
