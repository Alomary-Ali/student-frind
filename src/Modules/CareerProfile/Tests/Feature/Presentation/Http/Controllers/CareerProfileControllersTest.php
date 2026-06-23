<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Tests\Feature\Presentation\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Tests\TestCase;

final class CareerProfileControllersTest extends TestCase
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
            'student_number' => 'STU-CTRL-01',
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

    public function test_auto_creates_profile_on_first_visit(): void
    {
        $response = $this->actingAs($this->user)->get(route('career.index'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('career_profiles', [
            'student_id' => $this->student->id,
        ]);
    }

    public function test_can_update_career_profile_basics(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.update'), [
            'major' => 'علوم الحاسب الآلي',
            'summary' => 'مطور برمجيات طموح',
            'interests' => ['AI', 'Web Development'],
            'languages' => ['العربية', 'English'],
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('career_profiles', [
            'student_id' => $this->student->id,
            'major' => 'علوم الحاسب الآلي',
        ]);
    }

    public function test_update_validates_required_major(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.update'), [
            'summary' => 'ملخص بدون تخصص',
        ]);

        $response->assertSessionHasErrors('major');
    }

    public function test_can_add_portfolio_item(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.portfolio.store'), [
            'title' => 'مشروع رفيق الطالب',
            'description' => 'منصة متكاملة لإرشاد الطلاب',
            'project_url' => 'https://rafiq.test',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-01',
            'technologies' => ['Laravel', 'Vue.js'],
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('portfolio_items', [
            'title' => 'مشروع رفيق الطالب',
        ]);
    }

    public function test_portfolio_item_validates_required_fields(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.portfolio.store'), [
            'description' => 'وصف بدون عنوان',
        ]);

        $response->assertSessionHasErrors(['title', 'start_date']);
    }

    public function test_can_add_experience(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.experience.store'), [
            'company' => 'شركة جوجل',
            'position' => 'مطور برمجيات متدرب',
            'description' => 'تطوير ميزات البحث',
            'start_date' => '2026-01-01',
            'is_current' => 1,
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('experiences', [
            'company' => 'شركة جوجل',
            'is_current' => true,
        ]);
    }

    public function test_experience_validates_required_fields(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.experience.store'), [
            'company' => 'شركة',
        ]);

        $response->assertSessionHasErrors(['position', 'description', 'start_date']);
    }

    public function test_can_create_career_goal(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $targetDate = date('Y-m-d', strtotime('+3 months'));
        $response = $this->actingAs($this->user)->post(route('career.goals.store'), [
            'title' => 'الحصول على شهادة AWS',
            'target_date' => $targetDate,
        ]);

        $response->assertRedirect(route('career.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('career_goals', [
            'title' => 'الحصول على شهادة AWS',
            'status' => 'not_started',
        ]);
    }

    public function test_career_goal_validates_required_fields(): void
    {
        $this->actingAs($this->user)->get(route('career.index'));

        $response = $this->actingAs($this->user)->post(route('career.goals.store'), []);

        $response->assertSessionHasErrors(['title', 'target_date']);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('career.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_student_is_redirected_home(): void
    {
        $advisor = User::factory()->create(['role' => 'advisor']);

        $response = $this->actingAs($advisor)->get(route('career.index'));

        $response->assertRedirect(route('home'));
    }
}
