<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Application\DTOs\AcademicAlertDto;
use Modules\Academic\Application\Mappers\AcademicMapper;
use Modules\Academic\Application\UseCases\GetStudentAlerts;
use Modules\Academic\Domain\Contracts\AcademicAlertRepositoryInterface;
use Modules\Academic\Domain\Entities\AcademicAlert;
use Modules\Academic\Domain\Enums\AlertSeverity;
use Modules\Academic\Domain\Enums\AlertType;
use Modules\Academic\Domain\ValueObjects\AlertId;
use Modules\Academic\Domain\ValueObjects\StudentId;
use PHPUnit\Framework\TestCase;

final class GetStudentAlertsTest extends TestCase
{
    private AcademicAlertRepositoryInterface $alerts;
    private AcademicMapper $mapper;
    private GetStudentAlerts $useCase;

    protected function setUp(): void
    {
        $this->alerts = $this->createMock(AcademicAlertRepositoryInterface::class);
        $this->mapper = new AcademicMapper();
        $this->useCase = new GetStudentAlerts($this->alerts, $this->mapper);
    }

    public function test_returns_empty_array_when_no_alerts_exist(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';
        $this->alerts->expects($this->once())
            ->method('findByStudentId')
            ->with(StudentId::fromString($studentId))
            ->willReturn([]);

        $result = $this->useCase->execute($studentId);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_returns_alerts_when_alerts_exist(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';
        $alertId = AlertId::fromString('660e8400-e29b-41d4-a716-446655440070');

        $alert = AcademicAlert::create(
            id: $alertId,
            studentId: StudentId::fromString($studentId),
            alertType: AlertType::LowGpa,
            severity: AlertSeverity::High,
            message: 'GPA is too low',
        );

        $dto = new AcademicAlertDto(
            id: $alertId->value(),
            studentId: $studentId,
            alertType: AlertType::LowGpa->value,
            severity: AlertSeverity::High->value,
            message: 'GPA is too low',
            metadata: null,
            isResolved: false,
            createdAt: $alert->createdAt()->format('c'),
            resolvedAt: null,
            resolvedBy: null,
        );

        $this->alerts->expects($this->once())
            ->method('findByStudentId')
            ->with(StudentId::fromString($studentId))
            ->willReturn([$alert]);

        $result = $this->useCase->execute($studentId);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(AcademicAlertDto::class, $result[0]);
        $this->assertEquals($dto->id, $result[0]->id);
        $this->assertEquals($dto->message, $result[0]->message);
    }

    public function test_returns_only_unresolved_alerts(): void
    {
        $studentId = '550e8400-e29b-41d4-a716-446655440099';
        $alertId1 = AlertId::fromString('660e8400-e29b-41d4-a716-446655440071');
        $alertId2 = AlertId::fromString('660e8400-e29b-41d4-a716-446655440072');

        $alert1 = AcademicAlert::create(
            id: $alertId1,
            studentId: StudentId::fromString($studentId),
            alertType: AlertType::LowGpa,
            severity: AlertSeverity::High,
            message: 'GPA is too low',
        );

        $alert2 = AcademicAlert::create(
            id: $alertId2,
            studentId: StudentId::fromString($studentId),
            alertType: AlertType::CreditDeficit,
            severity: AlertSeverity::Medium,
            message: 'Credit deficit',
        );

        $this->alerts->expects($this->once())
            ->method('findUnresolvedByStudentId')
            ->with(StudentId::fromString($studentId))
            ->willReturn([$alert1, $alert2]);

        $result = $this->useCase->executeUnresolved($studentId);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(AcademicAlertDto::class, $result[0]);
        $this->assertEquals('GPA is too low', $result[0]->message);
    }
}
