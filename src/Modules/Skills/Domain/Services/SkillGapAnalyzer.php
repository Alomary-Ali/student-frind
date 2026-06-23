<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Services;

use Modules\Skills\Domain\Entities\SkillProfile;

final class SkillGapAnalyzer
{
    private static array $roleRequiredSkills = [
        'frontend_developer' => ['HTML', 'CSS', 'JavaScript', 'React', 'Git', 'TypeScript', 'TailwindCSS'],
        'backend_developer' => ['PHP', 'Laravel', 'SQL', 'Git', 'REST APIs', 'Docker', 'Testing'],
        'fullstack_developer' => ['HTML', 'CSS', 'JavaScript', 'React', 'PHP', 'Laravel', 'SQL', 'Git', 'REST APIs'],
        'data_scientist' => ['Python', 'SQL', 'Data Analysis', 'Machine Learning', 'Statistics', 'Pandas'],
        'cybersecurity_analyst' => ['Networking', 'Linux', 'Security Fundamentals', 'Cryptography', 'Python', 'Ethical Hacking'],
    ];

    public function analyze(SkillProfile $profile, string $roleKey): array
    {
        $roleKey = mb_strtolower($roleKey);
        if (! array_key_exists($roleKey, self::$roleRequiredSkills)) {
            return [
                'role' => $roleKey,
                'current_skills' => [],
                'missing_skills' => [],
                'matching_percentage' => 0,
            ];
        }

        $required = self::$roleRequiredSkills[$roleKey];
        $currentSkills = [];
        foreach ($profile->skills() as $skill) {
            $currentSkills[] = mb_strtolower($skill->name());
        }

        $matching = [];
        $missing = [];

        foreach ($required as $reqSkill) {
            if (in_array(mb_strtolower($reqSkill), $currentSkills)) {
                $matching[] = $reqSkill;
            } else {
                $missing[] = $reqSkill;
            }
        }

        $percentage = count($required) > 0 ? (int) round((count($matching) / count($required)) * 100) : 0;

        return [
            'role' => $roleKey,
            'current_skills' => $matching,
            'missing_skills' => $missing,
            'matching_percentage' => $percentage,
        ];
    }

    public static function getRoles(): array
    {
        return [
            'frontend_developer' => 'مطور واجهات أمامية (Frontend Developer)',
            'backend_developer' => 'مطور أنظمة خلفية (Backend Developer)',
            'fullstack_developer' => 'مطور شامل (Fullstack Developer)',
            'data_scientist' => 'عالم بيانات (Data Scientist)',
            'cybersecurity_analyst' => 'محلل أمن سيبراني (Cybersecurity Analyst)',
        ];
    }
}
