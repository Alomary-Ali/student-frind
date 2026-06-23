<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Modules\CareerProfile\Application\UseCases\CreateCareerProfile;
use Modules\CareerProfile\Application\UseCases\GetCareerProfile;
use Modules\CareerProfile\Application\UseCases\UpdateCareerProfile;
use Modules\CareerProfile\Application\UseCases\AddPortfolioItem;
use Modules\CareerProfile\Application\UseCases\AddExperience;
use Modules\CareerProfile\Application\UseCases\CreateCareerGoal;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;

final readonly class CareerProfileController
{
    public function __construct(
        private CreateCareerProfile $createCareerProfile,
        private GetCareerProfile $getCareerProfile,
        private UpdateCareerProfile $updateCareerProfile,
        private AddPortfolioItem $addPortfolioItem,
        private AddExperience $addExperience,
        private CreateCareerGoal $createCareerGoal,
    ) {
    }

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $profile = null;

        if ($studentId) {
            $profile = $this->getCareerProfile->execute($studentId);

            // Auto-create if not exists
            if ($profile === null) {
                $profile = $this->createCareerProfile->execute(
                    studentId: $studentId,
                    major: 'غير محدد',
                );
            }
        }

        return view('career.index', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (!$studentId) {
            return redirect()->route('career.index')->with('error', 'الملف الشخصي غير موجود');
        }

        try {
            $this->updateCareerProfile->execute($studentId, $request->all());
            return redirect()->route('career.index')->with('success', 'تم تحديث الملف الشخصي بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء التحديث')->withInput();
        }
    }

    public function storePortfolioItem(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (!$studentId) {
            return redirect()->route('career.index')->with('error', 'الملف الشخصي غير موجود');
        }

        try {
            $this->addPortfolioItem->execute($studentId, $request->all());
            return redirect()->route('career.index')->with('success', 'تمت إضافة المشروع بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة المشروع')->withInput();
        }
    }

    public function storeExperience(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (!$studentId) {
            return redirect()->route('career.index')->with('error', 'الملف الشخصي غير موجود');
        }

        try {
            $this->addExperience->execute($studentId, $request->all());
            return redirect()->route('career.index')->with('success', 'تمت إضافة الخبرة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الخبرة')->withInput();
        }
    }

    public function storeCareerGoal(Request $request): RedirectResponse
    {
        $studentId = $this->resolveStudentId($request);

        if (!$studentId) {
            return redirect()->route('career.index')->with('error', 'الملف الشخصي غير موجود');
        }

        try {
            $this->createCareerGoal->execute($studentId, $request->all());
            return redirect()->route('career.index')->with('success', 'تمت إضافة الهدف المهني بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الهدف')->withInput();
        }
    }

    private function resolveStudentId(Request $request): ?string
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }

        $student = EloquentStudent::where('user_id', $user->id)->first();
        return $student?->id;
    }
}
