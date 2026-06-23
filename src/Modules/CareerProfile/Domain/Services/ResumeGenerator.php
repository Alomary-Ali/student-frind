<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Services;

use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\Skills\Domain\Entities\SkillProfile;

final class ResumeGenerator
{
    public function generate(
        CareerProfile $profile,
        ?SkillProfile $skillProfile,
        string $studentName,
        string $studentEmail
    ): string {
        $markdown = "# " . $studentName . "\n";
        $markdown .= "**البريد الإلكتروني:** " . $studentEmail . " | **التخصص:** " . $profile->major() . "\n\n";

        if ($profile->summary()) {
            $markdown .= "## الملخص المهني\n";
            $markdown .= $profile->summary() . "\n\n";
        }

        if (count($profile->experiences()) > 0) {
            $markdown .= "## الخبرات المهنية\n";
            foreach ($profile->experiences() as $exp) {
                $endDateStr = $exp->isCurrent() ? 'الآن' : ($exp->endDate()?->format('Y-m') ?? '');
                $markdown .= "### " . $exp->position() . " - " . $exp->company() . "\n";
                $markdown .= "*" . $exp->startDate()->format('Y-m') . " إلى " . $endDateStr . "*\n";
                $markdown .= $exp->description() . "\n\n";
            }
        }

        if (count($profile->portfolioItems()) > 0) {
            $markdown .= "## المشاريع البرمجية والشخصية\n";
            foreach ($profile->portfolioItems() as $item) {
                $markdown .= "### " . $item->title() . "\n";
                $markdown .= "*" . $item->startDate()->format('Y-m') . "*\n";
                $markdown .= $item->description() . "\n";
                if ($item->githubUrl()) {
                    $markdown .= "- GitHub: " . $item->githubUrl() . "\n";
                }
                if ($item->projectUrl()) {
                    $markdown .= "- رابط المشروع: " . $item->projectUrl() . "\n";
                }
                $markdown .= "**التقنيات المستخدمة:** " . implode(', ', $item->technologies()) . "\n\n";
            }
        }

        if ($skillProfile !== null && count($skillProfile->skills()) > 0) {
            $markdown .= "## المهارات\n";
            $skillsByCategory = [];
            foreach ($skillProfile->skills() as $skill) {
                $categoryLabel = $skill->category()->label();
                $skillsByCategory[$categoryLabel][] = $skill->name() . " (" . $skill->level()->label() . ")";
            }

            foreach ($skillsByCategory as $category => $skillsList) {
                $markdown .= "- **" . $category . ":** " . implode(', ', $skillsList) . "\n";
            }
            $markdown .= "\n";
        }

        if ($skillProfile !== null && count($skillProfile->certifications()) > 0) {
            $markdown .= "## الشهادات المهنية\n";
            foreach ($skillProfile->certifications() as $cert) {
                $markdown .= "- **" . $cert->name() . "** - الجهة المانحة: " . $cert->issuer() . " (" . $cert->issueDate()->format('Y-m') . ")\n";
            }
            $markdown .= "\n";
        }

        return $markdown;
    }
}
