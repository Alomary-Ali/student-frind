<?php

declare(strict_types=1);

namespace Tests\Security\Productivity;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentAssignment;
use Tests\TestCase;

final class ProductivitySecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_requires_authentication(): void
    {
        $response = $this->post('/productivity/assignments', [
            'user_id' => (string) \Illuminate\Support\Str::uuid(),
            'course_id' => 'CS101',
            'title' => 'واجب البرمجة',
            'description' => 'حل مسائل البرمجة الأساسية',
            'due_date' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertRedirect('/login');
    }

    public function test_exam_requires_authentication(): void
    {
        $response = $this->post('/productivity/exams', [
            'user_id' => (string) \Illuminate\Support\Str::uuid(),
            'course_id' => 'CS101',
            'title' => 'اختبار منتصف الفصل',
            'exam_type' => 'midterm',
            'exam_date' => now()->addDays(14)->toDateTimeString(),
            'location' => 'قاعة A',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_project_requires_authentication(): void
    {
        $response = $this->post('/productivity/projects', [
            'user_id' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'مشروع تطوير تطبيق الويب',
            'description' => 'تطوير تطبيق ويب لإدارة المهام',
            'start_date' => now()->toDateTimeString(),
            'due_date' => now()->addDays(60)->toDateTimeString(),
        ]);

        $response->assertRedirect('/login');
    }

    public function test_rate_limiting_on_assignment_creation(): void
    {
        $user = \App\Models\User::factory()->create();

        for ($i = 0; $i < 31; $i++) {
            $this->actingAs($user)->post('/productivity/assignments', [
                'user_id' => $user->id,
                'course_id' => 'CS101',
                'title' => "واجب {$i}",
                'description' => 'حل مسائل البرمجة الأساسية',
                'due_date' => now()->addDays(7)->toDateTimeString(),
            ]);
        }

        $response = $this->actingAs($user)->post('/productivity/assignments', [
            'user_id' => $user->id,
            'course_id' => 'CS101',
            'title' => 'واجب 31',
            'description' => 'حل مسائل البرمجة الأساسية',
            'due_date' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertStatus(429);
    }
}
