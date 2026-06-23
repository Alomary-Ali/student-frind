<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Services;

use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Academic\Domain\ValueObjects\Gpa;

final class AcademicAlertService
{
    /**
     * Check if student has low GPA and should be alerted.
     */
    public function checkLowGpa(Gpa $currentGpa, Gpa $threshold = null): bool
    {
        $threshold = $threshold ?? Gpa::of(2.0);
        return $currentGpa->value() < $threshold->value();
    }

    /**
     * Check if student has credit deficit.
     */
    public function checkCreditDeficit(int $creditsEarned, int $creditsRequired, int $expectedCredits): bool
    {
        return ($creditsRequired - $creditsEarned) > $expectedCredits;
    }

    /**
     * Create a low GPA alert.
     */
    public function createLowGpaAlert(
        StudentId $studentId,
        Gpa $currentGpa,
        Gpa $threshold,
    ): AcademicAlert {
        return AcademicAlert::create(
            id: AlertId::generate(),
            studentId: $studentId,
            alertType: AlertType::LowGpa,
            severity: $currentGpa->value() < 1.5 ? AlertSeverity::Critical : AlertSeverity::High,
            message: sprintf('GPA الحالي %.2f أقل من الحد الأدنى %.2f', $currentGpa->value(), $threshold->value()),
            metadata: [
                'current_gpa' => $currentGpa->value(),
                'threshold' => $threshold->value(),
            ],
        );
    }

    /**
     * Create a credit deficit alert.
     */
    public function createCreditDeficitAlert(
        StudentId $studentId,
        int $creditsEarned,
        int $creditsRequired,
        int $expectedCredits,
    ): AcademicAlert {
        $deficit = $creditsRequired - $creditsEarned;
        return AcademicAlert::create(
            id: AlertId::generate(),
            studentId: $studentId,
            alertType: AlertType::CreditDeficit,
            severity: $deficit > 12 ? AlertSeverity::High : AlertSeverity::Medium,
            message: sprintf('نقص في الساعات المعتمدة: تحتاج %d ساعة إضافية', $deficit),
            metadata: [
                'credits_earned' => $creditsEarned,
                'credits_required' => $creditsRequired,
                'expected_credits' => $expectedCredits,
                'deficit' => $deficit,
            ],
        );
    }

    /**
     * Create a graduation delay alert.
     */
    public function createGraduationDelayAlert(
        StudentId $studentId,
        int $semestersRemaining,
        int $expectedSemesters,
    ): AcademicAlert {
        return AcademicAlert::create(
            id: AlertId::generate(),
            studentId: $studentId,
            alertType: AlertType::GraduationDelay,
            severity: $semestersRemaining > $expectedSemesters ? AlertSeverity::High : AlertSeverity::Medium,
            message: sprintf('خطر تأخر التخرج: بقي %d فصل بينما المتوقع %d فصل', $semestersRemaining, $expectedSemesters),
            metadata: [
                'semesters_remaining' => $semestersRemaining,
                'expected_semesters' => $expectedSemesters,
            ],
        );
    }
}
