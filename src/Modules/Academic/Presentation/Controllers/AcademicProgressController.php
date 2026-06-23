<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Application\Queries\GetGraduationProgress;
use Modules\Academic\Application\Queries\GetStudentAcademicProfile;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;

final class AcademicProgressController extends Controller
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly GetStudentAcademicProfile $getStudentProfile,
        private readonly GetGraduationProgress $getGraduationProgress,
    ) {}

    public function __invoke(Request $request): View
    {
        $userId = (string) $request->user()->id;
        $student = $this->students->findByUserId($userId);

        if ($student === null) {
            return view('academic.progress', [
                'profile' => null,
                'graduationProgress' => null,
                'error' => 'لا يوجد ملف أكاديمي. يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي',
            ]);
        }

        $studentId = $student->id()->value();

        try {
            $profile = $this->getStudentProfile->execute($studentId);
            $graduationProgress = $this->getGraduationProgress->execute($studentId);

            return view('academic.progress', [
                'profile' => $profile,
                'graduationProgress' => $graduationProgress,
                'error' => null,
            ]);
        } catch (StudentNotFoundException $e) {
            return view('academic.progress', [
                'profile' => null,
                'graduationProgress' => null,
                'error' => 'لا يوجد ملف أكاديمي. يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي',
            ]);
        }
    }
}
