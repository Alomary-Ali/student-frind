<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Services;

use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\Skills\Domain\Entities\SkillProfile;

final class CareerScoreCalculator
{
    public function calculate(
        CareerProfile $profile,
        ?SkillProfile $skillProfile,
        float $gpa,
    ): int {
        // 1. Skills (25%)
        $skillsScore = 0;
        if ($skillProfile !== null && count($skillProfile->skills()) > 0) {
            $skillsCount = count($skillProfile->skills());
            // 5+ skills = max score of 25
            $skillsScore = min(25, (int) round(($skillsCount / 5) * 25));
        }

        // 2. GPA (20%)
        // GPA is out of 4.0. Max GPA (4.0) gives 20 points.
        $gpaScore = min(20, (int) round(($gpa / 4.0) * 20));

        // 3. Projects (20%)
        // 3+ projects give maximum points (20).
        $projectsCount = count($profile->portfolioItems());
        $projectsScore = min(20, (int) round(($projectsCount / 3) * 20));

        // 4. Certifications (15%)
        // 2+ certifications give maximum points (15).
        $certificationsCount = $skillProfile !== null ? count($skillProfile->certifications()) : 0;
        $certificationsScore = min(15, (int) round(($certificationsCount / 2) * 15));

        // 5. Experience (10%)
        // 1+ experience gives maximum points (10).
        $experienceCount = count($profile->experiences());
        $experienceScore = min(10, (int) round(($experienceCount / 1) * 10));

        // 6. Activities/Goals (10%)
        // Completed goals. 2+ completed goals give maximum points (10).
        $completedGoals = 0;
        foreach ($profile->careerGoals() as $goal) {
            if ($goal->status()->value === 'completed') {
                $completedGoals++;
            }
        }
        $activitiesScore = min(10, (int) round(($completedGoals / 2) * 10));

        return $skillsScore + $gpaScore + $projectsScore + $certificationsScore + $experienceScore + $activitiesScore;
    }
}
