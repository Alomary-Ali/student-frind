<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Integrations;

use Modules\Career\Domain\Contracts\AiCareerServiceInterface;

final class AiCareerService implements AiCareerServiceInterface
{
    public function generateAdvice(string $studentId, string $query): string
    {
        return 'هذه نصيحة مبنية على ملفك المهني. ركز على تطوير مهاراتك التقنية والناعمة، وابحث عن فرص تدريب تعزز خبرتك العملية.';
    }

    public function reviewResume(string $resumeContent): array
    {
        return [
            'score' => 75,
            'strengths' => ['تنظيم جيد', 'مهارات تقنية واضحة'],
            'improvements' => ['أضف إنجازات قابلة للقياس', 'حسّن الملخص الشخصي'],
            'suggestions' => ['استخدم أفعال عمل قوية', 'أضف روابط المشاريع'],
        ];
    }

    public function generateInterviewQuestions(string $role, string $type): array
    {
        $questions = [
            'technical' => [
                'اشرح مفهوم REST API',
                'ما هي الفروق بين SQL و NoSQL؟',
                'كيف تتعامل مع الأخطاء في التطبيق؟',
            ],
            'behavioral' => [
                'حدثني عن موقف واجهت فيه تحدياً كبيراً',
                'كيف تعمل ضمن فريق؟',
                'صف مشروع قمت بقيادته',
            ],
            'general' => [
                'لماذا اخترت هذا المجال؟',
                'أين ترى نفسك بعد 5 سنوات؟',
                'ما هي أكبر نقاط قوتك؟',
            ],
        ];

        $selected = $questions[$type] ?? $questions['general'];

        return array_map(fn ($q, $i) => [
            'id' => (string) ($i + 1),
            'question' => $q,
            'category' => $type,
            'order' => $i + 1,
        ], $selected, array_keys($selected));
    }

    public function analyzeSkillGap(array $skills, string $targetRole): array
    {
        $roleSkills = [
            'backend-developer' => ['PHP', 'Laravel', 'MySQL', 'REST API', 'Git', 'Docker', 'Testing'],
            'frontend-developer' => ['HTML', 'CSS', 'JavaScript', 'React', 'TypeScript', 'Git'],
            'data-analyst' => ['Python', 'SQL', 'Excel', 'Statistics', 'Data Visualization', 'Machine Learning'],
            'devops-engineer' => ['Linux', 'Docker', 'Kubernetes', 'CI/CD', 'Cloud', 'Terraform'],
            'fullstack-developer' => ['PHP', 'JavaScript', 'HTML', 'CSS', 'Database', 'Git', 'REST API'],
        ];

        $required = $roleSkills[$targetRole] ?? ['مهارات تقنية', 'مهارات تواصل', 'حل المشكلات'];
        $currentSkillNames = array_map(fn ($s) => is_string($s) ? $s : ($s['name'] ?? ''), $skills);
        $currentSkillNames = array_filter($currentSkillNames);

        $matched = array_intersect($required, $currentSkillNames);
        $missing = array_diff($required, $currentSkillNames);

        return [
            'target_role' => $targetRole,
            'total_required' => count($required),
            'matched_count' => count($matched),
            'matched_skills' => array_values($matched),
            'missing_skills' => array_values($missing),
            'percentage' => count($required) > 0 ? round((count($matched) / count($required)) * 100, 1) : 0,
        ];
    }

    public function matchOpportunities(string $studentId): array
    {
        return [
            'matched' => [],
            'total_opportunities' => 0,
            'message' => 'AI matching available when AI module is connected',
        ];
    }
}
