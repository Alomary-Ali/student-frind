<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Modules\StudentServices\Application\UseCases\SendMessage;
use Modules\StudentServices\Application\UseCases\StartConversation;
use Modules\StudentServices\Domain\Contracts\ConversationRepositoryInterface;
use Modules\StudentServices\Infrastructure\Persistence\EloquentConversationRepository;
use Tests\TestCase;

final class AiAssistantIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private ConversationRepositoryInterface $conversationRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->conversationRepository = new EloquentConversationRepository;
    }

    public function test_conversation_creation_and_messaging_flow(): void
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

        // Step 1: Start conversation
        $startConversation = new StartConversation(
            $this->conversationRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $conversationDto = $startConversation->execute(
            studentId: $user->id,
            title: 'استفسار عن التسجيل',
            contextData: ['topic' => 'registration'],
        );

        $this->assertNotNull($conversationDto);
        $this->assertEquals('active', $conversationDto->status);

        // Step 2: Send message
        $sendMessage = new SendMessage(
            $this->conversationRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $messageDto = $sendMessage->execute(
            conversationId: $conversationDto->id,
            content: 'كيف أسجل في المقررات؟',
            role: 'user',
        );

        $this->assertNotNull($messageDto);
        $this->assertEquals('user', $messageDto->role);
    }

    public function test_conversation_persistence_and_retrieval(): void
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

        $startConversation = new StartConversation(
            $this->conversationRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $conversationDto = $startConversation->execute($user->id, 'استفسار');

        // Retrieve from database
        $retrieved = $this->conversationRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ConversationId::fromString($conversationDto->id),
        );

        $this->assertNotNull($retrieved);
        $this->assertEquals($conversationDto->id, $retrieved->id()->value());
        $this->assertEquals($user->id, $retrieved->studentId());
    }

    public function test_conversation_status_transitions(): void
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

        $startConversation = new StartConversation(
            $this->conversationRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $conversationDto = $startConversation->execute($user->id, 'استفسار');

        $conversation = $this->conversationRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ConversationId::fromString($conversationDto->id),
        );

        // Test status transitions
        $conversation->close();
        $this->assertEquals('closed', $conversation->status()->value);

        $conversation->archive();
        $this->assertEquals('archived', $conversation->status()->value);
    }

    public function test_conversation_context_updates(): void
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

        $startConversation = new StartConversation(
            $this->conversationRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $conversationDto = $startConversation->execute($user->id, 'استفسار', ['topic' => 'general']);

        $conversation = $this->conversationRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ConversationId::fromString($conversationDto->id),
        );

        $conversation->updateContext(['language' => 'ar', 'step' => 1]);
        $this->conversationRepository->save($conversation);

        $retrieved = $this->conversationRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\ConversationId::fromString($conversationDto->id),
        );

        $this->assertArrayHasKey('topic', $retrieved->contextData());
        $this->assertArrayHasKey('language', $retrieved->contextData());
        $this->assertArrayHasKey('step', $retrieved->contextData());
    }

    public function test_student_conversations_history(): void
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

        $startConversation = new StartConversation(
            $this->conversationRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\Gateways\AiAssistantGatewayInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $startConversation->execute($user->id, 'استفسار 1');
        $startConversation->execute($user->id, 'استفسار 2');
        $startConversation->execute($user->id, 'استفسار 3');

        $conversations = $this->conversationRepository->findByStudentId($user->id);

        $this->assertCount(3, $conversations);
    }
}
