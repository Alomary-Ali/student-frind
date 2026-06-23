<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Feature\Presentation\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Tests\TestCase;

final class SkillsControllersTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private EloquentStudent $student;
    private string $studentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'student']);
        $this->studentId = (string) \Illuminate\Support\Str::uuid();
        $this->student = EloquentStudent::create([
            'id' => $this->studentId,
            'user_id' => $this->user->id,
            'student_number' => 'CTRL-' . rand(10000, 99999),
            'academic_status' => 'enrolled',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.6,
        ]);
    }

    public function test_guest_cannot_access_skills_page(): void
    {
        $response = $this->get(route('skills.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_index_returns_view_with_profile(): void
    {
        $response = $this->actingAs($this->user)->get(route('skills.index'));

        $response->assertStatus(200);
        $response->assertViewHas('profile');
        $response->assertViewHas('achievements');
        $response->assertViewHas('learningPaths');
    }

    public function test_store_skill_validation_fails(): void
    {
        $this->actingAs($this->user)->get(route('skills.index'));

        $response = $this->actingAs($this->user)->post(route('skills.skills.store'), [
            'name' => '',
            'category' => 'invalid_category',
            'level' => 'invalid_level',
        ]);

        $response->assertSessionHasErrors(['name', 'category', 'level']);
    }

    public function test_store_skill_creates_profile_automatically(): void
    {
        $this->actingAs($this->user)->get(route('skills.index'));

        $response = $this->actingAs($this->user)->post(route('skills.skills.store'), [
            'name' => 'Python',
            'category' => 'programming',
            'level' => 'intermediate',
            'years_of_experience' => 2,
        ]);

        $response->assertRedirect(route('skills.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('skill_profiles', [
            'student_id' => $this->studentId,
        ]);

        $this->assertDatabaseHas('skills', [
            'name' => 'Python',
            'category' => 'programming',
            'level' => 'intermediate',
            'years_of_experience' => 2,
        ]);
    }

    public function test_store_certification_validation_fails(): void
    {
        $this->actingAs($this->user)->get(route('skills.index'));

        $response = $this->actingAs($this->user)->post(route('skills.certifications.store'), [
            'name' => '',
            'issuer' => '',
            'issue_date' => 'not-a-date',
        ]);

        $response->assertSessionHasErrors(['name', 'issuer', 'issue_date']);
    }

    public function test_store_certification_creates_profile_automatically(): void
    {
        $this->actingAs($this->user)->get(route('skills.index'));

        $response = $this->actingAs($this->user)->post(route('skills.certifications.store'), [
            'name' => 'Cisco CCNA',
            'issuer' => 'Cisco Systems',
            'issue_date' => '2026-03-01',
            'expiry_date' => '2029-03-01',
            'credential_url' => 'https://cisco.com/cert/ccna',
            'verification_code' => 'CCNA-98765',
        ]);

        $response->assertRedirect(route('skills.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('certifications', [
            'name' => 'Cisco CCNA',
            'issuer' => 'Cisco Systems',
            'verification_code' => 'CCNA-98765',
        ]);
    }

    public function test_profile_is_reused_across_requests(): void
    {
        $this->actingAs($this->user)->get(route('skills.index'));

        $this->actingAs($this->user)->post(route('skills.skills.store'), [
            'name' => 'JavaScript',
            'category' => 'programming',
            'level' => 'advanced',
        ]);

        $this->actingAs($this->user)->post(route('skills.certifications.store'), [
            'name' => 'Meta Frontend',
            'issuer' => 'Meta',
            'issue_date' => '2026-04-01',
        ]);

        $this->assertDatabaseCount('skill_profiles', 1);
    }
}
