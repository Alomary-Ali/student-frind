<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Application\Queries\GetStudentAcademicProfile;
use Modules\Academic\Application\Queries\GetGraduationProgress;
use Modules\Academic\Application\UseCases\GetStudentAlerts;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;

final class AcademicDashboardController extends Controller
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly GetStudentAcademicProfile $getStudentProfile,
        private readonly GetGraduationProgress $getGraduationProgress,
        private readonly GetStudentAlerts $getStudentAlerts,
    ) {}

    public function __invoke(Request $request): View
    {
        $userId = (string) $request->user()->id;

        try {
            // Find student by user_id first
            $student = $this->students->findByUserId($userId);

            if ($student === null) {
                return view('academic.dashboard', [
                    'profile' => null,
                    'graduationProgress' => null,
                    'error' => 'لا يوجد ملف أكاديمي. يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي',
                ]);
            }

            $studentId = $student->id()->value();

            $profile = $this->getStudentProfile->execute($studentId);
            $graduationProgress = $this->getGraduationProgress->execute($studentId);
            $alerts = $this->getStudentAlerts->executeUnresolved($studentId);

            return view('academic.dashboard', [
                'profile' => $profile,
                'graduationProgress' => $graduationProgress,
                'alerts' => $alerts,
                'error' => null,
            ]);
        } catch (StudentNotFoundException $e) {
            return view('academic.dashboard', [
                'profile' => null,
                'graduationProgress' => null,
                'error' => 'لا يوجد ملف أكاديمي. يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي',
            ]);
        }
    }
}
