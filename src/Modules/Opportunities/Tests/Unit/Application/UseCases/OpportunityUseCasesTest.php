<?php

declare(strict_types=1);

namespace Modules\Opportunities\Tests\Unit\Application\UseCases;

use DateTimeImmutable;
use Modules\Opportunities\Application\Mappers\OpportunityMapper;
use Modules\Opportunities\Application\UseCases\CreateOpportunity;
use Modules\Opportunities\Application\UseCases\DeleteOpportunity;
use Modules\Opportunities\Application\UseCases\SaveOpportunity;
use Modules\Opportunities\Application\UseCases\UpdateOpportunity;
use Modules\Opportunities\Domain\Contracts\OpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Contracts\SavedOpportunityRepositoryInterface;
use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\Enums\OpportunityStatus;
use Modules\Opportunities\Domain\Enums\OpportunityType;
use Modules\Opportunities\Domain\Enums\Provider;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class OpportunityUseCasesTest extends TestCase
{
    private OpportunityRepositoryInterface $repository;
    private EventDispatcherInterface $dispatcher;
    private OpportunityMapper $mapper;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(OpportunityRepositoryInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->mapper = new OpportunityMapper;
    }

    public function test_create_opportunity(): void
    {
        $this->repository->expects($this->once())->method('save');
        $this->dispatcher->expects($this->once())->method('dispatch');

        $useCase = new CreateOpportunity($this->repository, $this->dispatcher, $this->mapper);

        $dto = $useCase->execute(
            type: 'job',
            title: 'مطور Laravel',
            description: 'مطلوب مطور Laravel',
            provider: 'linkedin',
            location: 'الرياض',
            country: 'السعودية',
            deadline: '+30 days',
            applyUrl: 'https://apply.com',
            metadata: ['company' => 'شركة'],
            tags: ['Laravel', 'PHP'],
        );

        $this->assertSame('مطور Laravel', $dto->title);
        $this->assertSame('job', $dto->type);
        $this->assertSame('الرياض', $dto->location);
        $this->assertNotEmpty($dto->id);
    }

    public function test_update_opportunity(): void
    {
        $id = OpportunityId::generate();
        $now = new DateTimeImmutable;

        $existing = Opportunity::reconstitute(
            id: $id,
            title: 'قديم',
            description: 'قديم',
            provider: Provider::LINKEDIN,
            type: OpportunityType::JOB,
            location: null,
            country: null,
            deadline: null,
            applyUrl: null,
            status: OpportunityStatus::ACTIVE,
            metadata: [],
            sourceUrl: null,
            imageUrl: null,
            tags: [],
            createdAt: $now,
            updatedAt: $now,
        );

        $this->repository->method('findById')->willReturn($existing);
        $this->repository->expects($this->once())->method('save');

        $useCase = new UpdateOpportunity($this->repository, $this->dispatcher, $this->mapper);

        $dto = $useCase->execute(
            id: $id->value(),
            title: 'جديد',
            description: 'وصف جديد',
            location: 'جدة',
            metadata: ['company' => 'شركة جديدة'],
        );

        $this->assertSame('جديد', $dto->title);
        $this->assertSame('جدة', $dto->location);
    }

    public function test_delete_opportunity(): void
    {
        $id = OpportunityId::generate();

        $this->repository->expects($this->once())->method('delete');

        $useCase = new DeleteOpportunity($this->repository);
        $useCase->execute($id->value());
    }

    public function test_save_opportunity_toggles(): void
    {
        $savedRepo = $this->createMock(SavedOpportunityRepositoryInterface::class);
        $savedRepo->method('isSaved')->willReturnOnConsecutiveCalls(false, true);

        $savedRepo->expects($this->once())->method('save');
        $savedRepo->expects($this->once())->method('delete');

        $useCase = new SaveOpportunity($savedRepo);

        $result1 = $useCase->execute('student-1', OpportunityId::generate()->value());
        $result2 = $useCase->execute('student-1', OpportunityId::generate()->value());

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }
}
