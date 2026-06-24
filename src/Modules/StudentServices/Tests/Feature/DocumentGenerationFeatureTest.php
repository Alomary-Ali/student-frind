<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DocumentGenerationFeatureTest extends TestCase
{
    use RefreshDatabase;
    use WithAuthenticatedStudent;

    public function test_student_can_request_document(): void
    {
        $user = $this->createAndAuthenticateStudent();

        $response = $this->postJson('/api/v1/student-services/documents', [
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
            'status' => 'generated',
        ]);
    }

    public function test_request_document_fails_with_invalid_type(): void
    {
        $this->createAndAuthenticateStudent();

        $response = $this->postJson('/api/v1/student-services/documents', [
            'document_type' => 'invalid_type',
        ]);

        $response->assertStatus(422);
    }

    public function test_student_can_list_their_documents(): void
    {
        $this->createAndAuthenticateStudent();

        $response = $this->getJson('/api/v1/student-services/documents');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [],
            ]);
    }

    public function test_student_can_verify_document(): void
    {
        $this->createAndAuthenticateStudent();

        $response = $this->postJson('/api/v1/student-services/documents/verify', [
            'verification_code' => 'VER-12345',
        ]);

        $response->assertStatus(200);
    }
}
