<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentKnowledgeCategory;
use Tests\TestCase;

final class KnowledgeBaseFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithAuthenticatedStudent;

    public function test_student_can_search_knowledge_base(): void
    {
        $this->createAndAuthenticateStudent();

        EloquentKnowledgeCategory::create([
            'id' => 'cat-001',
            'name' => 'التسجيل',
            'slug' => 'registration',
            'description' => 'مقالات عن التسجيل',
            'parent_id' => null,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/v1/student-services/knowledge?query=تسجيل');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [],
            ]);
    }

    public function test_student_can_view_knowledge_articles(): void
    {
        $this->createAndAuthenticateStudent();

        EloquentKnowledgeCategory::create([
            'id' => 'cat-001',
            'name' => 'التسجيل',
            'slug' => 'registration',
            'description' => 'وصف',
            'parent_id' => null,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/v1/student-services/knowledge');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [],
            ]);
    }

    public function test_unauthenticated_user_cannot_list_knowledge(): void
    {
        $response = $this->getJson('/api/v1/student-services/knowledge');

        $response->assertStatus(401);
    }
}
