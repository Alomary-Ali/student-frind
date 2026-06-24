<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class AssistantChatFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_start_conversation(): void
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

        $response = $this->postJson('/api/v1/student-services/assistant/conversations', [
            'title' => 'استفسار عن التسجيل',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'student_id',
                    'title',
                    'status',
                ],
            ]);

        $this->assertDatabaseHas('assistant_conversations', [
            'student_id' => $user->id,
            'status' => 'active',
        ]);
    }

    public function test_student_can_send_message(): void
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

        $conversationResponse = $this->postJson('/api/v1/student-services/assistant/conversations', [
            'title' => 'استفسار',
        ]);
        $conversationId = $conversationResponse->json('data.id');

        $response = $this->postJson("/api/v1/student-services/assistant/conversations/{$conversationId}/messages", [
            'content' => 'كيف أسجل في المقررات؟',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'conversation_id',
                    'role',
                    'content',
                ],
            ]);
    }

    public function test_student_can_get_conversation_history(): void
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

        $response = $this->getJson('/api/v1/student-services/assistant/conversations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [],
            ]);
    }

    public function test_unauthenticated_user_cannot_start_conversation(): void
    {
        $response = $this->postJson('/api/v1/student-services/assistant/conversations', [
            'title' => 'استفسار',
        ]);

        $response->assertStatus(401);
    }
}
