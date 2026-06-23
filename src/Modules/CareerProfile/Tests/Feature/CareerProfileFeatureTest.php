<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\CareerProfile\Infrastructure\Persistence\Eloquent\EloquentCareerProfile;
use Tests\TestCase;

final class CareerProfileFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private EloquentStudent $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'student']);
        $this->student = EloquentStudent::create([
            'id' => \Illuminate\Support\Str::uuid()->toString(),
            'user_id' => $this->user->id,
            'student_number' => 'STU-12345',
            'academic_status' => 'enrolled',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.5,
        ]);
    }

    public function test_can_view_career_profile_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('career.index'));

        $response->assertStatus(200)
            ->assertViewHas('profile')
            ->assertSee('بوابة التطوير المهني');
    }

    public function test_can_update_career_profile_basics(): void
    {
        // View the page once to auto-create the profile
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.update'), [
            'major' => 'علوم الحاسب الآلي',
            'summary' => 'مطور برمجيات طموح وشغوف بالذكاء الاصطناعي',
            'interests' => ['AI', 'Web Development'],
            'languages' => ['العربية', 'English'],
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('career_profiles', [
            'student_id' => $this->student->id,
            'major' => 'علوم الحاسب الآلي',
            'summary' => 'مطور برمجيات طموح وشغوف بالذكاء الاصطناعي',
        ]);
    }

    public function test_can_add_portfolio_item(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.portfolio.store'), [
            'title' => 'مشروع رفيق الطالب',
            'description' => 'منصة متكاملة لإرشاد الطلاب أكاديمياً ومهنياً',
            'project_url' => 'https://rafiq.test',
            'github_url' => 'https://github.com/rafiq/student-companion',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-01',
            'technologies' => ['Laravel', 'Vue.js', 'TailwindCSS'],
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('portfolio_items', [
            'title' => 'مشروع رفيق الطالب',
            'project_url' => 'https://rafiq.test',
        ]);
    }

    public function test_can_add_experience(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.experience.store'), [
            'company' => 'شركة جوجل العالمية',
            'position' => 'مطور برمجيات متدرب',
            'description' => 'العمل على تطوير ميزات البحث والتصنيف بالذكاء الاصطناعي',
            'start_date' => '2026-01-01',
            'is_current' => 1,
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('experiences', [
            'company' => 'شركة جوجل العالمية',
            'position' => 'مطور برمجيات متدرب',
            'is_current' => true,
        ]);
    }

    public function test_can_create_career_goal(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.goals.store'), [
            'title' => 'الحصول على شهادة AWS Solutions Architect',
            'target_date' => date('Y-m-d', strtotime('+3 months')),
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('career_goals', [
            'title' => 'الحصول على شهادة AWS Solutions Architect',
            'status' => 'not_started',
        ]);
    }
}
