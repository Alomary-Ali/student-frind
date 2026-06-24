<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Modules\StudentServices\Infrastructure\Persistence\EloquentKnowledgeCategory;
use Tests\TestCase;

final class KnowledgeBaseFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_search_knowledge_base(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);
        Sanctum::actingAs($user);

        $category = EloquentKnowledgeCategory::create([
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

    public function test_student_can_view_knowledge_article(): void
    {
        $user = EloquentUser::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'email' => 'student@test.com',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'password_hash' => Hash::make('password'),
            'role' => 'student',
            'status' => 'active',
            'academic_id' => null,
        ]);
        Sanctum::actingAs($user);

        $category = EloquentKnowledgeCategory::create([
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

    public function test_unauthenticated_user_can_view_published_articles(): void
    {
        $response = $this->getJson('/api/v1/student-services/knowledge');

        $response->assertStatus(200);
    }
}
