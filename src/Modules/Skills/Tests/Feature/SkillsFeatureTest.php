<?php

declare(strict_types=1);

namespace Modules\Skills\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Tests\TestCase;

final class SkillsFeatureTest extends TestCase
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
            'student_number' => 'STU-54321',
            'academic_status' => 'enrolled',
            'academic_standing' => 'good_standing',
            'cumulative_gpa' => 3.8,
        ]);
    }

    public function test_can_view_skills_hub_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('skills.index'));

        $response->assertStatus(200)
            ->assertViewHas('profile')
            ->assertSee('مركز المهارات والجدارات');
    }

    public function test_can_add_skill_to_profile(): void
    {
        $this->actingAs($this->user)->get(route('skills.index'));

        $response = $this->actingAs($this->user)->post(route('skills.skills.store'), [
            'name' => 'Laravel Framework',
            'category' => 'programming',
            'level' => 'advanced',
            'years_of_experience' => 3,
        ]);

        $response->assertRedirect(route('skills.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('skills', [
            'name' => 'Laravel Framework',
            'category' => 'programming',
            'level' => 'advanced',
            'years_of_experience' => 3,
        ]);
    }

    public function test_can_add_certification_to_profile(): void
    {
        $this->actingAs($this->user)->get(route('skills.index'));

        $response = $this->actingAs($this->user)->post(route('skills.certifications.store'), [
            'name' => 'Oracle Java Certified Associate',
            'issuer' => 'Oracle Corporation',
            'issue_date' => '2025-05-15',
            'expiry_date' => '2030-05-15',
            'credential_url' => 'https://oracle.com/cert/java-associate',
            'verification_code' => 'JCA-10293847',
        ]);

        $response->assertRedirect(route('skills.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('certifications', [
            'name' => 'Oracle Java Certified Associate',
            'issuer' => 'Oracle Corporation',
            'verification_code' => 'JCA-10293847',
        ]);
    }
}
