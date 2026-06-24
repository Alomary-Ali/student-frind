<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\GenerateDocument;
use Modules\StudentServices\Application\UseCases\ListStudentDocuments;
use Modules\StudentServices\Application\UseCases\RequestDocument;
use Modules\StudentServices\Application\UseCases\VerifyDocument;

final readonly class DocumentApiController
{
    public function __construct(
        private RequestDocument $requestDocument,
        private GenerateDocument $generateDocument,
        private VerifyDocument $verifyDocument,
        private ListStudentDocuments $listStudentDocuments,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return response()->json(['success' => false, 'message' => 'Student profile not found'], 400);
        }

        $entities = $this->listStudentDocuments->execute($studentId);

        $data = array_map(fn ($d): array => [
            'id' => $d->id()->value(),
            'student_id' => $d->studentId(),
            'type' => $d->type()->value,
            'title' => $d->title(),
            'file_path' => $d->filePath(),
            'status' => $d->status()->value,
            'verification_code' => $d->verificationCode(),
            'created_at' => $d->createdAt()->format('c'),
        ], $entities);

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request): JsonResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return response()->json(['success' => false, 'message' => 'Student profile not found'], 400);
        }

        $result = $this->generateDocument->execute(
            $studentId,
            $request->input('document_type'),
            $request->except(['document_type']),
        );

        return response()->json(['success' => true, 'data' => $result], 201);
    }

    public function verify(Request $request): JsonResponse
    {
        $code = $request->input('verification_code');

        if (! $code) {
            return response()->json(['success' => false, 'message' => 'Verification code is required'], 422);
        }

        $result = $this->verifyDocument->execute($code);

        if ($result === null) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $result]);
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
