<?php

declare(strict_types=1);

namespace Tests\Feature\Productivity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Productivity\Domain\Events\ExamCreated;
use Modules\Productivity\Infrastructure\Persistence\Eloquent\EloquentExam;
use Tests\TestCase;

final class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_exam(): void
    {
        Event::fake([ExamCreated::class]);
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/productivity/exams', [
            'user_id' => $user->id,
            'course_id' => 'CS101',
            'title' => 'اختبار منتصف الفصل',
            'exam_type' => 'midterm',
            'exam_date' => now()->addDays(14)->toDateTimeString(),
            'location' => 'قاعة A',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('productivity_exams', [
            'title' => 'اختبار منتصف الفصل',
            'course_id' => 'CS101',
        ]);
        Event::assertDispatched(ExamCreated::class);
    }

    public function test_can_update_exam_status(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $exam = EloquentExam::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'course_id' => 'CS101',
            'title' => 'اختبار منتصف الفصل',
            'exam_type' => 'midterm',
            'exam_date' => now()->addDays(14),
            'location' => 'قاعة A',
            'status' => 'scheduled',
        ]);

        $response = $this->post("/productivity/exams/{$exam->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('productivity_exams', [
            'id' => $exam->id,
            'status' => 'completed',
        ]);
    }
}
