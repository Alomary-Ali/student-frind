<?php

declare(strict_types=1);

namespace Modules\StudentServices\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Shared\Infrastructure\Persistence\EloquentUser;
use Modules\StudentServices\Application\UseCases\GenerateDocument;
use Modules\StudentServices\Application\UseCases\RequestDocument;
use Modules\StudentServices\Application\UseCases\VerifyDocument;
use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;
use Modules\StudentServices\Domain\Contracts\DocumentRequestRepositoryInterface;
use Modules\StudentServices\Infrastructure\Persistence\EloquentDocumentRepository;
use Modules\StudentServices\Infrastructure\Persistence\EloquentDocumentRequestRepository;
use Tests\TestCase;

final class DocumentGeneratorIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private DocumentRepositoryInterface $documentRepository;
    private DocumentRequestRepositoryInterface $documentRequestRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->documentRepository = new EloquentDocumentRepository;
        $this->documentRequestRepository = new EloquentDocumentRequestRepository;
    }

    public function test_document_request_and_generation_flow(): void
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

        // Step 1: Request document
        $requestDocument = new RequestDocument(
            $this->documentRequestRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $requestDto = $requestDocument->execute(
            studentId: $user->id,
            documentType: 'certificate',
            notes: 'أحتاج الشهادة للعمل',
        );

        $this->assertNotNull($requestDto);
        $this->assertEquals('pending', $requestDto->status);

        // Step 2: Generate document
        $generateDocument = new GenerateDocument(
            $this->documentRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\DocumentGeneratorInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $documentDto = $generateDocument->execute(
            studentId: $user->id,
            documentType: 'certificate',
            title: 'شهادة تخرج',
            metadata: ['semester' => '2026-1'],
        );

        $this->assertNotNull($documentDto);
        $this->assertEquals('pending', $documentDto->status);

        // Step 3: Verify document
        $verifyDocument = new VerifyDocument(
            $this->documentRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $verifiedDto = $verifyDocument->execute(
            documentId: $documentDto->id,
            verifierId: 'admin-1',
        );

        $this->assertEquals('verified', $verifiedDto->status);
    }

    public function test_document_persistence_and_retrieval(): void
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

        $generateDocument = new GenerateDocument(
            $this->documentRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\DocumentGeneratorInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $documentDto = $generateDocument->execute($user->id, 'transcript', 'كشف درجات');

        // Retrieve from database
        $retrieved = $this->documentRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\DocumentId::fromString($documentDto->id),
        );

        $this->assertNotNull($retrieved);
        $this->assertEquals($documentDto->id, $retrieved->id()->value());
        $this->assertEquals($user->id, $retrieved->studentId());
        $this->assertEquals('transcript', $retrieved->type()->value);
    }

    public function test_document_status_transitions(): void
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

        $generateDocument = new GenerateDocument(
            $this->documentRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\DocumentGeneratorInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $documentDto = $generateDocument->execute($user->id, 'certificate', 'شهادة');

        $document = $this->documentRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\DocumentId::fromString($documentDto->id),
        );

        // Test status transitions
        $document->generate('/path/to/cert.pdf', 'VER-12345');
        $this->assertEquals('generated', $document->status()->value);
        $this->assertEquals('/path/to/cert.pdf', $document->filePath());
        $this->assertEquals('VER-12345', $document->verificationCode());

        $document->verify('admin-1');
        $this->assertEquals('verified', $document->status()->value);
    }

    public function test_student_documents_filtering(): void
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

        $generateDocument = new GenerateDocument(
            $this->documentRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\DocumentGeneratorInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $generateDocument->execute($user->id, 'certificate', 'شهادة 1');
        $generateDocument->execute($user->id, 'transcript', 'كشف درجات');
        $generateDocument->execute($user->id, 'statement', 'بيان');

        $documents = $this->documentRepository->findByStudentId($user->id);

        $this->assertCount(3, $documents);
    }

    public function test_document_verification_code_uniqueness(): void
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

        $generateDocument = new GenerateDocument(
            $this->documentRepository,
            $this->createMock(\Modules\Shared\Domain\Contracts\EventDispatcherInterface::class),
            $this->createMock(\Modules\StudentServices\Domain\Contracts\DocumentGeneratorInterface::class),
            new \Modules\StudentServices\Application\Mappers\StudentServicesMapper,
        );

        $documentDto1 = $generateDocument->execute($user->id, 'certificate', 'شهادة 1');
        $documentDto2 = $generateDocument->execute($user->id, 'certificate', 'شهادة 2');

        $document1 = $this->documentRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\DocumentId::fromString($documentDto1->id),
        );
        $document2 = $this->documentRepository->findById(
            \Modules\StudentServices\Domain\ValueObjects\DocumentId::fromString($documentDto2->id),
        );

        $document1->generate('/path1.pdf', 'VER-001');
        $document2->generate('/path2.pdf', 'VER-002');

        $this->assertNotEquals($document1->verificationCode(), $document2->verificationCode());
    }
}
