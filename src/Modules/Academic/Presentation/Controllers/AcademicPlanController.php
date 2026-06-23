<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Application\Queries\GetCurriculumCourses;
use Modules\Academic\Application\Queries\GetGraduationProgress;
use Modules\Academic\Application\Queries\GetStudentAcademicProfile;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;

final class AcademicPlanController extends Controller
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly GetStudentAcademicProfile $getStudentProfile,
        private readonly GetGraduationProgress $getGraduationProgress,
        private readonly GetCurriculumCourses $getCurriculumCourses,
    ) {}

    public function __invoke(Request $request): View
    {
        $userId = (string) $request->user()->id;
        $student = $this->students->findByUserId($userId);

        if ($student === null) {
            return view('academic.plan', [
                'profile' => null,
                'graduationProgress' => null,
                'groupedCourses' => [],
                'courses' => [],
                'error' => 'لا يوجد ملف أكاديمي. يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي',
            ]);
        }

        $studentId = $student->id()->value();

        try {
            $profile = $this->getStudentProfile->execute($studentId);
            $graduationProgress = $this->getGraduationProgress->execute($studentId);
            $courses = $this->getCurriculumCourses->execute($userId);

            $grouped = [];
            foreach ($courses as $course) {
                $semester = $course['semester_order'];
                $year = (int) ceil($semester / 2);
                $label = 'المستوى ' . $year . ' - الفصل ' . ($semester % 2 === 0 ? 'الثاني' : 'الأول');
                if (! isset($grouped[$label])) {
                    $grouped[$label] = [];
                }
                $grouped[$label][] = $course;
            }

            return view('academic.plan', [
                'profile' => $profile,
                'graduationProgress' => $graduationProgress,
                'groupedCourses' => $grouped,
                'courses' => $courses,
                'error' => null,
            ]);
        } catch (StudentNotFoundException $e) {
            return view('academic.plan', [
                'profile' => null,
                'graduationProgress' => null,
                'groupedCourses' => [],
                'courses' => [],
                'error' => 'لا يوجد ملف أكاديمي. يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي',
            ]);
        }
    }
}
