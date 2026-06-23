<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Academic\Domain\Contracts\AcademicPlanReaderInterface;
use Modules\Academic\Domain\Contracts\StudentRepositoryInterface;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentSkillProfile;

final class PulseBarDataResolver
{
    private const CACHE_TTL = 300;

    public function __construct(
        private StudentRepositoryInterface $studentRepo,
        private AcademicPlanReaderInterface $planReader,
    ) {}

    public function resolve(string $userId): array
    {
        $cacheKey = $this->cacheKey($userId);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            return $this->fetchFreshData($userId);
        });
    }

    public function refresh(string $userId): array
    {
        $cacheKey = $this->cacheKey($userId);
        $data = $this->fetchFreshData($userId);
        Cache::put($cacheKey, $data, self::CACHE_TTL);

        return $data;
    }

    public function forget(string $userId): void
    {
        Cache::forget($this->cacheKey($userId));
    }

    private function cacheKey(string $userId): string
    {
        return 'pulsebar_' . $userId;
    }

    private function fetchFreshData(string $userId): array
    {
        try {
            $student = $this->studentRepo->findByUserId($userId);
        } catch (\Throwable) {
            return $this->defaults();
        }

        if ($student === null) {
            return $this->defaults();
        }

        try {
            $studentId = $student->id()->value();

            $gpa = 0.0;
            $progress = 0.0;
            $readiness = 75;

            $profile = $this->planReader->getStudentProfile($studentId);
            if ($profile !== null) {
                $gpa = $profile->cumulativeGpa;
            }

            $graduationProgress = $this->planReader->getGraduationProgress($studentId);
            if ($graduationProgress !== null) {
                $progress = $graduationProgress->completionPercentage;
            }

            $skillsCount = null;

            try {
                $skillProfile = EloquentSkillProfile::where('student_id', $studentId)->first();
                if ($skillProfile) {
                    $skillsCount = $skillProfile->skills()->count();
                }
            } catch (\Throwable) {
            }

            $coursesCount = null;

            try {
                $courses = $student->enrollments();
                $coursesCount = is_countable($courses) ? count($courses) : 0;
            } catch (\Throwable) {
            }

            return [
                'gpa' => $gpa,
                'progress' => $progress,
                'readiness' => $readiness,
                'skills' => $skillsCount,
                'courses' => $coursesCount,
            ];
        } catch (\Throwable) {
            return $this->defaults();
        }
    }

    private function defaults(): array
    {
        return [
            'gpa' => 0,
            'progress' => 0,
            'readiness' => 0,
            'skills' => null,
            'courses' => null,
        ];
    }
}
