<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Services;

use Modules\CareerProfile\Domain\Entities\CareerProfile;
use Modules\Skills\Domain\Entities\SkillProfile;

final class LinkedInOptimizer
{
    public function optimize(CareerProfile $profile, ?SkillProfile $skillProfile): array
    {
        $score = 0;
        $recommendations = [];

        // 1. Check Headline / Major
        if (! empty($profile->major())) {
            $score += 20;
        } else {
            $recommendations[] = 'قم بإضافة المسمى المهني أو التخصص الدراسي الخاص بك إلى عنوان ملفك الشخصي.';
        }

        // 2. Check Summary / About Section
        $summaryLength = mb_strlen($profile->summary());
        if ($summaryLength > 100) {
            $score += 20;
        } elseif ($summaryLength > 0) {
            $score += 10;
            $recommendations[] = 'الملخص المهني الخاص بك قصير جداً. اكتب فقرة متكاملة (أكثر من 100 حرف) تشرح فيها أهدافك وخبراتك.';
        } else {
            $recommendations[] = 'أضف ملخصاً تعريفياً (About) لملفك المهني لجذب مسؤولي التوظيف.';
        }

        // 3. Check Experience
        $expCount = count($profile->experiences());
        if ($expCount >= 2) {
            $score += 20;
        } elseif ($expCount === 1) {
            $score += 10;
            $recommendations[] = 'إضافة المزيد من الخبرات المهنية أو التطوعية تزيد من مصداقية ملفك.';
        } else {
            $recommendations[] = 'لم تقم بإضافة أي خبرة مهنية. أضف خبرات سابقة، أو تدريبات عملية، أو أعمال تطوعية.';
        }

        // 4. Check Skills
        $skillsCount = $skillProfile ? count($skillProfile->skills()) : 0;
        if ($skillsCount >= 5) {
            $score += 20;
        } elseif ($skillsCount > 0) {
            $score += 10;
            $recommendations[] = 'ينصح بإضافة 5 مهارات على الأقل في حسابك الشخصي على LinkedIn.';
        } else {
            $recommendations[] = 'أضف مهاراتك الفنية والشخصية لكي يتمكن أصحاب العمل من العثور عليك.';
        }

        // 5. Check Certifications / Projects
        $certCount = $skillProfile ? count($skillProfile->certifications()) : 0;
        $projectsCount = count($profile->portfolioItems());

        if ($certCount > 0 || $projectsCount > 0) {
            $score += 20;
        } else {
            $recommendations[] = 'أضف قسماً للشهادات المهنية أو المشاريع البرمجية والشخصية التي أنجزتها.';
        }

        return [
            'score' => $score,
            'recommendations' => $recommendations,
        ];
    }
}
