<?php

declare(strict_types=1);

namespace Modules\Career\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Career\Application\UseCases\CalculateEmploymentReadiness;
use Modules\Career\Application\UseCases\GetComprehensiveDashboard;
use Modules\Career\Application\UseCases\GetUnifiedRecommendations;

final readonly class DashboardController
{
    public function __construct(
        private GetComprehensiveDashboard $getDashboard,
        private CalculateEmploymentReadiness $readiness,
        private GetUnifiedRecommendations $recommendations,
    ) {}

    public function index(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $gpa = $request->input('gpa');

        $dashboard = $this->getDashboard->execute(
            $studentId,
            $gpa !== null ? (float) $gpa : null,
        );

        return view('career.dashboard', [
            'dashboard' => $dashboard,
        ]);
    }

    public function readiness(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);
        $gpa = $request->input('gpa');

        $result = $this->readiness->execute(
            $studentId,
            $gpa !== null ? (float) $gpa : null,
        );

        return view('career.readiness', [
            'score' => $result['score'],
            'breakdown' => $result['breakdown'],
        ]);
    }

    public function recommendations(Request $request): View
    {
        $studentId = $this->resolveStudentId($request);

        $recommendations = $this->recommendations->execute($studentId);

        return view('career.recommendations', [
            'recommendations' => $recommendations,
        ]);
    }

    private function resolveStudentId(Request $request): string
    {
        $user = $request->user();

        if ($user === null) {
            throw new \RuntimeException('User not authenticated');
        }

        $student = \Modules\Academic\Infrastructure\Persistence\EloquentStudent::where('user_id', $user->id)->first();

        if ($student === null) {
            throw new \RuntimeException('Student profile not found');
        }

        return $student->id;
    }
}
