<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Services;

use Modules\Academic\Domain\Entities\Student;

final class EarlyWarningService
{
    /**
     * Generate academic alerts for a student based on their performance.
     *
     * @param  array<string, mixed>  $graduationProgress
     * @return array<string, mixed>
     */
    public function generateAlerts(Student $student, array $graduationProgress): array
    {
        $alerts = [];

        // Check for low GPA
        if ($student->cumulativeGpa() < 2.0) {
            $alerts[] = [
                'alert_type' => 'low_gpa',
                'severity' => 'high',
                'message' => sprintf('GPA is below 2.0 (%.2f). Academic probation risk.', $student->cumulativeGpa()),
                'metadata' => [
                    'current_gpa' => $student->cumulativeGpa(),
                    'threshold' => 2.0,
                ],
            ];
        } elseif ($student->cumulativeGpa() < 2.5) {
            $alerts[] = [
                'alert_type' => 'low_gpa',
                'severity' => 'medium',
                'message' => sprintf('GPA is below 2.5 (%.2f). Consider academic support.', $student->cumulativeGpa()),
                'metadata' => [
                    'current_gpa' => $student->cumulativeGpa(),
                    'threshold' => 2.5,
                ],
            ];
        }

        // Check for graduation delay risk
        if (isset($graduationProgress['on_track']) && ! $graduationProgress['on_track']) {
            $alerts[] = [
                'alert_type' => 'graduation_delay',
                'severity' => 'high',
                'message' => 'Graduation delay risk detected. Current pace may delay graduation.',
                'metadata' => [
                    'on_track' => false,
                    'completion_percentage' => $graduationProgress['completion_percentage'] ?? 0,
                ],
            ];
        }

        // Check for credit deficit
        if (isset($graduationProgress['credits_earned']) && isset($graduationProgress['credits_required'])) {
            $creditsRemaining = $graduationProgress['credits_required'] - $graduationProgress['credits_earned'];
            if ($creditsRemaining > 30) {
                $alerts[] = [
                    'alert_type' => 'credit_deficit',
                    'severity' => 'medium',
                    'message' => sprintf('Large credit deficit: %d credits remaining.', $creditsRemaining),
                    'metadata' => [
                        'credits_remaining' => $creditsRemaining,
                        'credits_earned' => $graduationProgress['credits_earned'],
                        'credits_required' => $graduationProgress['credits_required'],
                    ],
                ];
            }
        }

        return $alerts;
    }
}
