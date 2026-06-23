<?php

declare(strict_types=1);

namespace Modules\Opportunities\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Academic\Infrastructure\Persistence\EloquentStudent;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentOpportunity;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class OpportunitiesControllerTest extends TestCase
{
    use RefreshDatabase;

    private \App\Models\User $user;
    private EloquentStudent $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = \App\Models\User::factory()->create([
            'role' => 'student',
        ]);

        $this->student = EloquentStudent::create([
            'id' => 'student-test-1',
            'user_id' => $this->user->id,
            'student_number' => 'SN-2024001',
            'academic_status' => 'active',
            'academic_standing' => 'good',
        ]);
    }

    protected function loginAsStudent(): void
    {
        $this->actingAs($this->user);
    }

    #[Test]
    public function index_page_returns_200_for_authenticated_student(): void
    {
        $this->loginAsStudent();

        $response = $this->get(route('opportunities.index'));

        $response->assertStatus(200);
        $response->assertSee('مركز الفرص');
    }

    #[Test]
    public function index_page_redirects_if_not_authenticated(): void
    {
        $response = $this->get(route('opportunities.index'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function index_page_shows_opportunities(): void
    {
        EloquentOpportunity::create([
            'id' => '00000000-0000-0000-0000-000000000001',
            'title' => 'فرصة اختبار',
            'description' => 'هذه فرصة اختبار',
            'provider' => 'manual',
            'type' => 'job',
            'status' => 'active',
        ]);

        $this->loginAsStudent();

        $response = $this->get(route('opportunities.index'));

        $response->assertStatus(200);
        $response->assertSee('فرصة اختبار');
    }

    #[Test]
    public function recommended_page_returns_200_for_authenticated_student(): void
    {
        $this->loginAsStudent();

        $response = $this->get(route('opportunities.recommended'));

        $response->assertStatus(200);
        $response->assertSee('الفرص الموصى بها');
    }

    #[Test]
    public function saved_page_returns_200_for_authenticated_student(): void
    {
        $this->loginAsStudent();

        $response = $this->get(route('opportunities.saved'));

        $response->assertStatus(200);
        $response->assertSee('الفرص المحفوظة');
    }

    #[Test]
    public function applications_page_returns_200_for_authenticated_student(): void
    {
        $this->loginAsStudent();

        $response = $this->get(route('opportunities.applications'));

        $response->assertStatus(200);
        $response->assertSee('طلبات التقديم');
    }

    #[Test]
    public function scholarships_page_returns_200(): void
    {
        $this->loginAsStudent();

        $response = $this->get(route('opportunities.scholarships'));

        $response->assertStatus(200);
    }

    #[Test]
    public function jobs_page_returns_200(): void
    {
        $this->loginAsStudent();

        $response = $this->get(route('opportunities.jobs'));

        $response->assertStatus(200);
    }

    #[Test]
    public function internships_page_returns_200(): void
    {
        $this->loginAsStudent();

        $response = $this->get(route('opportunities.internships'));

        $response->assertStatus(200);
    }
}
