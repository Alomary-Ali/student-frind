<?php

declare(strict_types=1);

namespace Modules\Productivity\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Productivity\Application\DTOs\CreateExamDto;
use Modules\Productivity\Application\UseCases\CreateExam;
use Modules\Productivity\Application\UseCases\UpdateExamStatus;
use Modules\Productivity\Domain\Contracts\ExamRepositoryInterface;
use Modules\Productivity\Presentation\Http\Requests\CreateExamRequest;

final class ExamController extends Controller
{
    public function __construct(
        private readonly CreateExam $createExam,
        private readonly UpdateExamStatus $updateExamStatus,
        private readonly ExamRepositoryInterface $examRepository,
    ) {}

    public function index(): View
    {
        $userId = \Modules\Shared\Domain\ValueObjects\UserId::fromString(auth()->id());
        $exams = $this->examRepository->findByUserId($userId);
        return view('productivity.exams', compact('exams'));
    }

    public function show(string $id): View
    {
        $examId = \Modules\Productivity\Domain\ValueObjects\ExamId::fromString($id);
        $exam = $this->examRepository->findById($examId);
        return view('productivity.exam-detail', compact('exam'));
    }

    public function store(CreateExamRequest $request): JsonResponse
    {
        $dto = CreateExamDto::fromRequest($request);
        $result = $this->createExam->execute($dto);

        return response()->json($result, 201);
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);

        $result = $this->updateExamStatus->execute(
            $id,
            $validated['status']
        );

        return response()->json($result);
    }
}
