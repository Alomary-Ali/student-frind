<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Tests\TestCase;

final class DocumentGenerationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_request_document(): void
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

        $response = $this->postJson('/api/v1/student-services/documents/request', [
            'document_type' => 'certificate',
            'notes' => 'أحتاج الشهادة للعمل',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'student_id',
                    'type',
                    'title',
                    'status',
                ],
            ]);

        $this->assertDatabaseHas('student_documents', [
            'student_id' => $user->id,
            'type' => 'certificate',
            'status' => 'pending',
        ]);
    }

    public function test_request_document_fails_with_invalid_type(): void
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

        $response = $this->postJson('/api/v1/student-services/documents/request', [
            'document_type' => 'invalid_type',
        ]);

        $response->assertStatus(422);
    }

    public function test_student_can_list_their_documents(): void
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

        $response = $this->getJson('/api/v1/student-services/documents');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [],
            ]);
    }

    public function test_student_can_verify_document(): void
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

        $response = $this->postJson('/api/v1/student-services/documents/verify', [
            'verification_code' => 'VER-12345',
        ]);

        $response->assertStatus(200);
    }
}
