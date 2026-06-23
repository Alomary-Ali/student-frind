<?php

declare(strict_types=1);

namespace Modules\Skills\Domain\Services;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Skills\Domain\Entities\LearningPath;
use Modules\Skills\Domain\ValueObjects\LearningPathId;

final class LearningPathGenerator
{
    public function generate(
        LearningPathId $id,
        StudentId $studentId,
        string $roleKey,
        array $missingSkills
    ): LearningPath {
        $steps = [];
        $index = 1;
        foreach ($missingSkills as $skill) {
            $steps[] = [
                'id' => 'step-' . $index,
                'title' => 'تعلم واكتسب مهارة: ' . $skill,
                'description' => 'قم بدراسة المفاهيم الأساسية وتطبيق مشاريع عملية متعلقة بـ ' . $skill,
                'completed' => false,
                'completed_at' => null,
            ];
            $index++;
        }

        // Add a final project step
        $steps[] = [
            'id' => 'step-' . $index,
            'title' => 'بناء مشروع نهائي متكامل',
            'description' => 'تطبيق جميع المهارات المكتسبة لبناء مشروع حقيقي يضاف إلى ملفك المهني.',
            'completed' => false,
            'completed_at' => null,
        ];

        $roleLabels = [
            'frontend_developer' => 'مطور واجهات أمامية',
            'backend_developer' => 'مطور أنظمة خلفية',
            'fullstack_developer' => 'مطور شامل',
            'data_scientist' => 'عالم بيانات',
            'cybersecurity_analyst' => 'محلل أمن سيبراني',
        ];

        $roleTitle = $roleLabels[$roleKey] ?? $roleKey;
        $title = 'مسار التعلم لمهنة: ' . $roleTitle;

        $targetDate = (new DateTimeImmutable())->modify('+' . (count($steps) * 2) . ' weeks');

        return LearningPath::create($id, $studentId, $title, $roleKey, $steps, $targetDate);
    }
}
