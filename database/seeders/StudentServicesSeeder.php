<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentServiceCategory;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentServiceWorkflow;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentWorkflowStep;

final class StudentServicesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedServiceCategories();
        $this->seedServiceWorkflows();
    }

    private function seedServiceCategories(): void
    {
        $categories = [
            [
                'id' => (string) Str::uuid(),
                'name' => 'إثبات قيد دراسي',
                'type' => 'academic',
                'description' => 'شهادة إثبات قيد دراسي للطلاب المسجلين',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'كشف درجات',
                'type' => 'academic',
                'description' => 'كشف درجات رسمي معتمد',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'شهادة تخرج',
                'type' => 'academic',
                'description' => 'شهادة تخرج للطلاب المنتهين',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'بيان درجات',
                'type' => 'academic',
                'description' => 'بيان درجات تفصيلي',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'بطاقة طالب',
                'type' => 'administrative',
                'description' => 'بطاقة هوية طالب رسمية',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'خطاب رسمي',
                'type' => 'administrative',
                'description' => 'خطاب رسمي للجهات الخارجية',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'تأجيل دراسي',
                'type' => 'administrative',
                'description' => 'طلب تأجيل دراسي لفصل دراسي',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'انسحاب من مقرر',
                'type' => 'academic',
                'description' => 'طلب الانسحاب من مقرر دراسي',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            EloquentServiceCategory::create($category);
        }
    }

    private function seedServiceWorkflows(): void
    {
        $categories = EloquentServiceCategory::all();

        foreach ($categories as $category) {
            $workflow = EloquentServiceWorkflow::create([
                'id' => (string) Str::uuid(),
                'service_category_id' => $category->id,
                'name' => "سير عمل {$category->name}",
                'status' => 'active',
            ]);

            // Add workflow steps based on category type
            $steps = $this->getWorkflowSteps($category->type);

            foreach ($steps as $index => $step) {
                EloquentWorkflowStep::create([
                    'id' => (string) Str::uuid(),
                    'workflow_id' => $workflow->id,
                    'name' => $step['name'],
                    'type' => $step['type'],
                    'order' => $index + 1,
                    'config' => json_encode($step['config'] ?? []),
                    'assignee_role' => $step['assignee_role'],
                    'status' => 'active',
                ]);
            }
        }
    }

    private function getWorkflowSteps(string $categoryType): array
    {
        return match ($categoryType) {
            'academic' => [
                [
                    'name' => 'تقديم الطلب',
                    'type' => 'form',
                    'assignee_role' => 'student',
                    'config' => ['required_fields' => ['student_id', 'semester']],
                ],
                [
                    'name' => 'مراجعة أكاديمية',
                    'type' => 'approval',
                    'assignee_role' => 'academic_advisor',
                    'config' => ['auto_approve' => false],
                ],
                [
                    'name' => 'اعتماد القسم',
                    'type' => 'approval',
                    'assignee_role' => 'department_head',
                    'config' => ['auto_approve' => false],
                ],
                [
                    'name' => 'إصدار المستند',
                    'type' => 'document',
                    'assignee_role' => 'system',
                    'config' => ['template' => 'academic_certificate'],
                ],
                [
                    'name' => 'إشعار الطالب',
                    'type' => 'notification',
                    'assignee_role' => 'system',
                    'config' => ['channels' => ['in_app', 'email']],
                ],
            ],
            'administrative' => [
                [
                    'name' => 'تقديم الطلب',
                    'type' => 'form',
                    'assignee_role' => 'student',
                    'config' => ['required_fields' => ['student_id', 'reason']],
                ],
                [
                    'name' => 'مراجعة إدارية',
                    'type' => 'approval',
                    'assignee_role' => 'admin',
                    'config' => ['auto_approve' => false],
                ],
                [
                    'name' => 'معالجة الطلب',
                    'type' => 'document',
                    'assignee_role' => 'admin',
                    'config' => ['processing_time' => '3_days'],
                ],
                [
                    'name' => 'إشعار الطالب',
                    'type' => 'notification',
                    'assignee_role' => 'system',
                    'config' => ['channels' => ['in_app']],
                ],
            ],
            default => [
                [
                    'name' => 'تقديم الطلب',
                    'type' => 'form',
                    'assignee_role' => 'student',
                    'config' => [],
                ],
                [
                    'name' => 'مراجعة',
                    'type' => 'approval',
                    'assignee_role' => 'admin',
                    'config' => [],
                ],
                [
                    'name' => 'إكمال',
                    'type' => 'document',
                    'assignee_role' => 'system',
                    'config' => [],
                ],
            ],
        };
    }
}
