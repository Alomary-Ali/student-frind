<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Services;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\Achievement;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\Enums\AchievementType;
use Modules\Skills\Domain\ValueObjects\AchievementId;

final class AchievementUnlocker
{
    /**
     * @param  array<Achievement>  $existingAchievements
     * @return array<Achievement> Newly unlocked achievements
     */
    public function checkAndUnlock(
        StudentId $studentId,
        array $existingAchievements,
        ?SkillProfile $skillProfile,
        int $completedCoursesCount,
        int $completedTasksCount,
        int $completedGoalsCount,
    ): array {
        $unlocked = [];
        $existingTitles = array_map(fn ($a) => $a->title(), $existingAchievements);

        // Achievement 1: Academic Star (Completed 5+ courses)
        if (! in_array('النجم الأكاديمي', $existingTitles) && $completedCoursesCount >= 5) {
            $unlocked[] = Achievement::create(
                AchievementId::generate(),
                $studentId,
                AchievementType::ACADEMIC,
                'النجم الأكاديمي',
                'إكمال 5 مساقات دراسية بنجاح.',
                '/assets/badges/academic_star.png',
            );
        }

        // Achievement 2: Productivity Master (Completed 10+ tasks)
        if (! in_array('سيد الإنتاجية', $existingTitles) && $completedTasksCount >= 10) {
            $unlocked[] = Achievement::create(
                AchievementId::generate(),
                $studentId,
                AchievementType::PRODUCTIVITY,
                'سيد الإنتاجية',
                'إنجاز 10 مهام دراسية أو شخصية بنجاح.',
                '/assets/badges/productivity_master.png',
            );
        }

        // Achievement 3: Skill Collector (Acquired 5+ skills)
        $skillsCount = $skillProfile ? count($skillProfile->skills()) : 0;
        if (! in_array('جامع المهارات', $existingTitles) && $skillsCount >= 5) {
            $unlocked[] = Achievement::create(
                AchievementId::generate(),
                $studentId,
                AchievementType::CAREER,
                'جامع المهارات',
                'إضافة 5 مهارات مهنية إلى ملفك الشخصي.',
                '/assets/badges/skill_collector.png',
            );
        }

        // Achievement 4: Certified Specialist (Earned 1+ certification)
        $certsCount = $skillProfile ? count($skillProfile->certifications()) : 0;
        if (! in_array('الأخصائي المعتمد', $existingTitles) && $certsCount >= 1) {
            $unlocked[] = Achievement::create(
                AchievementId::generate(),
                $studentId,
                AchievementType::CAREER,
                'الأخصائي المعتمد',
                'الحصول على شهادة مهنية معتمدة وتوثيقها في ملفك.',
                '/assets/badges/certified_specialist.png',
            );
        }

        return $unlocked;
    }
}
