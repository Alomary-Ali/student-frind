<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface;
use Tests\TestCase;

final class AssistantChatFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithAuthenticatedStudent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(AiAssistantGatewayInterface::class, function () {
            return new class implements AiAssistantGatewayInterface
            {
                public function ask(string $conversationId, string $studentId, string $message, array $context = []): array
                {
                    return [
                        'content' => 'مرحباً! كيف يمكنني مساعدتك؟',
                        'reply' => 'مرحباً! كيف يمكنني مساعدتك؟',
                        'tokens_used' => 10,
                    ];
                }

                public function generateSuggestions(string $conversationId, string $messageId, array $context = []): array
                {
                    return [
                        ['type' => 'reply', 'title' => 'مساعدة', 'action_url' => '/help'],
                    ];
                }

                public function searchKnowledge(string $query): array
                {
                    return ['query' => $query, 'results' => []];
                }
            };
        });
    }

    public function test_student_can_start_conversation(): void
    {
        $user = $this->createAndAuthenticateStudent();

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
            'status' => 'active',
        ]);
    }

    public function test_student_can_send_message(): void
    {
        $this->createAndAuthenticateStudent();

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
                'data' => [],
            ]);
    }

    public function test_student_can_get_conversation_history(): void
    {
        $this->createAndAuthenticateStudent();

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
