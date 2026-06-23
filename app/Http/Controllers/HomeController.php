<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;

final class HomeController extends Controller
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly AcademicPlanReaderInterface $planReader,
    ) {}

    public function __invoke(Request $request): View
    {
        $userId = (string) $request->user()->id;
        $stats = [
            'gpa' => 0,
            'progress' => 0,
            'skills' => 0,
            'readiness' => 75,
            'courses' => 0,
        ];

        try {
            $student = $this->students->findByUserId($userId);
            if ($student) {
                $studentId = $student->id()->value();
                $stats['gpa'] = $student->cumulativeGpa()->value();

                $progressModel = $this->planReader->getGraduationProgress($studentId);
                if ($progressModel) {
                    $stats['progress'] = $progressModel->completionPercentage;
                }

                try {
                    $skillProfile = \Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentSkillProfile::where('student_id', $studentId)->first();
                    if ($skillProfile) {
                        $stats['skills'] = $skillProfile->skills()->count();
                    }
                } catch (\Throwable $e) {
                }

                $stats['courses'] = count($student->enrollments());
            }
        } catch (\Throwable $e) {
        }

        return view('home', compact('stats'));
    }
}
