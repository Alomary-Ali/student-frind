<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\GenerateDocument;
use Modules\StudentServices\Application\UseCases\ListStudentDocuments;
use Modules\StudentServices\Application\UseCases\RequestDocument;
use Modules\StudentServices\Application\UseCases\VerifyDocument;
use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\DocumentId;
use Modules\StudentServices\Infrastructure\Integrations\DocumentGeneratorInterface;
use Modules\StudentServices\Presentation\Http\Requests\DocumentRequestRequest;

final readonly class DocumentController
{
    public function __construct(
        private RequestDocument $requestDocument,
        private GenerateDocument $generateDocument,
        private VerifyDocument $verifyDocument,
        private ListStudentDocuments $listStudentDocuments,
        private DocumentRepositoryInterface $documents,
        private DocumentGeneratorInterface $documentGenerator,
    ) {}

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $documents = $studentId ? $this->listStudentDocuments->execute($studentId) : [];

        return view('student-services.documents.index', ['documents' => $documents]);
    }

    public function create(): View
    {
        return view('student-services.documents.create');
    }

    public function store(DocumentRequestRequest $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $result = $this->generateDocument->execute(
                $studentId,
                $request->input('document_type'),
                $request->except(['document_type']),
            );

            return redirect()->route('student-services.documents.show', $result['id'])
                ->with('success', 'تم إنشاء المستند بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء المستند')->withInput();
        }
    }

    public function show(string $id, Request $request): View
    {
        $document = $this->documents->findById(DocumentId::fromString($id));

        return view('student-services.documents.show', ['document' => $document]);
    }

    public function verify(string $code, Request $request): View
    {
        $result = $this->verifyDocument->execute($code);

        return view('student-services.documents.verify', [
            'document' => $result,
            'isValid' => $result !== null,
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
