<?php

declare(strict_types=1);

namespace Modules\Academic\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Application\Queries\GetCurriculumCourses;
use Modules\Academic\Domain\Exceptions\StudentNotFoundException;

final class ListCurriculumCoursesController extends Controller
{
    public function __construct(
        private readonly GetCurriculumCourses $getCurriculumCourses,
    ) {}

    public function __invoke(Request $request): View
    {
        $userId = (string) $request->user()->id;

        try {
            $courses = $this->getCurriculumCourses->execute($userId);

            return view('academic.curriculum-courses', [
                'courses' => $courses,
                'error' => null,
            ]);
        } catch (StudentNotFoundException $e) {
            return view('academic.curriculum-courses', [
                'courses' => [],
                'error' => 'لا يوجد ملف أكاديمي. يرجى التواصل مع الإدارة لإنشاء الملف الأكاديمي',
            ]);
        }
    }
}
