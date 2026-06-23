<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\StudentServices\Application\UseCases\ApproveServiceRequest;
use Modules\StudentServices\Application\UseCases\CancelServiceRequest;
use Modules\StudentServices\Application\UseCases\CompleteServiceRequest;
use Modules\StudentServices\Application\UseCases\CreateServiceNotification;
use Modules\StudentServices\Application\UseCases\CreateServiceRequest;
use Modules\StudentServices\Application\UseCases\ListServiceRequests;
use Modules\StudentServices\Application\UseCases\RejectServiceRequest as RejectServiceRequestUseCase;
use Modules\StudentServices\Application\UseCases\UpdateServiceRequest;
use Modules\StudentServices\Domain\Contracts\ServiceRequestRepositoryInterface;
use Modules\StudentServices\Domain\ValueObjects\ServiceRequestId;
use Modules\StudentServices\Presentation\Http\Requests\CreateServiceRequestRequest;
use Modules\StudentServices\Presentation\Http\Requests\RejectServiceRequest as RejectServiceRequestForm;

final readonly class ServiceRequestController
{
    public function __construct(
        private CreateServiceRequest $createServiceRequest,
        private UpdateServiceRequest $updateServiceRequest,
        private ApproveServiceRequest $approveServiceRequest,
        private RejectServiceRequestUseCase $rejectServiceRequest,
        private CompleteServiceRequest $completeServiceRequest,
        private CancelServiceRequest $cancelServiceRequest,
        private ListServiceRequests $listServiceRequests,
        private CreateServiceNotification $createServiceNotification,
        private ServiceRequestRepositoryInterface $requests,
    ) {}

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $status = $request->input('status');
        $requests = $studentId ? $this->listServiceRequests->execute($studentId, $status) : [];

        return view('student-services.requests.index', [
            'requests' => $requests,
            'currentStatus' => $status,
        ]);
    }

    public function create(): View
    {
        return view('student-services.requests.create');
    }

    public function store(CreateServiceRequestRequest $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $result = $this->createServiceRequest->execute(
                $studentId,
                $request->input('category_id'),
                $request->input('priority'),
                $request->input('notes'),
            );

            $this->createServiceNotification->execute(
                $studentId,
                'service_request_submitted',
                'تم تقديم طلب خدمة جديد',
                'تم تقديم طلب الخدمة رقم ' . $result['ref_number'] . ' بنجاح',
            );

            return redirect()->route('student-services.requests.show', $result['id'])
                ->with('success', 'تم تقديم الطلب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تقديم الطلب')->withInput();
        }
    }

    public function show(string $id, Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $requestEntity = $this->requests->findById(ServiceRequestId::fromString($id));

        return view('student-services.requests.show', [
            'request' => $requestEntity,
            'studentId' => $studentId,
        ]);
    }

    public function approve(string $id, Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $result = $this->approveServiceRequest->execute($id, $studentId);

            if ($result === null) {
                return redirect()->back()->with('error', 'الطلب غير موجود');
            }

            return redirect()->back()->with('success', 'تم اعتماد الطلب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء اعتماد الطلب');
        }
    }

    public function reject(string $id, RejectServiceRequestForm $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $result = $this->rejectServiceRequest->execute(
                $id,
                $studentId,
                $request->input('reason'),
            );

            if ($result === null) {
                return redirect()->back()->with('error', 'الطلب غير موجود');
            }

            return redirect()->back()->with('success', 'تم رفض الطلب');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفض الطلب');
        }
    }

    public function cancel(string $id, Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (! $studentId) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $result = $this->cancelServiceRequest->execute($id, 'تم الإلغاء من قبل الطالب');

            if ($result === null) {
                return redirect()->back()->with('error', 'الطلب غير موجود');
            }

            return redirect()->back()->with('success', 'تم إلغاء الطلب');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إلغاء الطلب');
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
