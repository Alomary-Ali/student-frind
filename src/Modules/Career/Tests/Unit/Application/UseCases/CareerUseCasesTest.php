<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Application\UseCases;

use DateTimeImmutable;
use Modules\Career\Application\DTOs\CareerPathDto;
use Modules\Career\Application\DTOs\ComprehensiveDashboardDto;
use Modules\Career\Application\DTOs\InterviewDto;
use Modules\Career\Application\DTOs\PublicPortfolioDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Application\UseCases\CalculateEmploymentReadiness;
use Modules\Career\Application\UseCases\ExploreCareerPaths;
use Modules\Career\Application\UseCases\GetCareerPathDetails;
use Modules\Career\Application\UseCases\GetComprehensiveDashboard;
use Modules\Career\Application\UseCases\GetPublicPortfolio;
use Modules\Career\Application\UseCases\IncrementPortfolioViews;
use Modules\Career\Application\UseCases\PublishPortfolio;
use Modules\Career\Application\UseCases\ScheduleInterview;
use Modules\Career\Domain\Contracts\CareerPathRepositoryInterface;
use Modules\Career\Domain\Contracts\Gateways\CareerProfileGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\OpportunitiesGatewayInterface;
use Modules\Career\Domain\Contracts\Gateways\SkillsGatewayInterface;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;
use Modules\Career\Domain\Contracts\PublicPortfolioRepositoryInterface;
use Modules\Career\Domain\Entities\CareerPath;
use Modules\Career\Domain\Entities\Interview;
use Modules\Career\Domain\Entities\PublicPortfolio;
use Modules\Career\Domain\Enums\PortfolioTheme;
use Modules\Career\Domain\ValueObjects\CareerPathId;
use Modules\Career\Domain\ValueObjects\InterviewId;
use Modules\Career\Domain\ValueObjects\PortfolioSlug;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

final class CareerUseCasesTest extends TestCase
{
    private CareerMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new CareerMapper;
    }

    public function test_it_schedules_interview(): void
    {
        $studentId = 'student-123';

        $repo = new class implements InterviewRepositoryInterface
        {
            public ?Interview $saved = null;

            public function findById(InterviewId $id): ?Interview
            {
                return null;
            }

            public function findByStudentId(string $studentId): array
            {
                return [];
            }

            public function save(Interview $interview): void
            {
                $this->saved = $interview;
            }

            public function delete(InterviewId $id): void {}
        };

        $events = new class implements EventDispatcherInterface
        {
            public array $dispatched = [];

            public function dispatch(array $events): void
            {
                $this->dispatched = array_merge($this->dispatched, $events);
            }
        };

        $useCase = new ScheduleInterview($repo, $events, $this->mapper);
        $dto = $useCase->execute($studentId, 'mock', '2026-07-15 10:00:00');

        $this->assertInstanceOf(InterviewDto::class, $dto);
        $this->assertSame($studentId, $dto->studentId);
        $this->assertSame('mock', $dto->type);
        $this->assertSame('scheduled', $dto->status);
        $this->assertNotNull($repo->saved);
        $this->assertCount(1, $events->dispatched);
    }

    public function test_it_explores_career_paths(): void
    {
        $path = CareerPath::create(
            CareerPathId::generate(),
            'Software Engineer',
            'Become a software engineer',
            'software_engineer',
            ['PHP', 'Laravel'],
            [],
            '$80,000',
            '15%',
        );

        $repo = new class($path) implements CareerPathRepositoryInterface
        {
            private CareerPath $path;

            public function __construct(CareerPath $path)
            {
                $this->path = $path;
            }

            public function findById(CareerPathId $id): ?CareerPath
            {
                return null;
            }

            public function findAll(): array
            {
                return [$this->path];
            }

            public function findByTargetRole(string $targetRole): array
            {
                return $this->path->targetRole() === $targetRole ? [$this->path] : [];
            }

            public function save(CareerPath $careerPath): void {}

            public function delete(CareerPathId $id): void {}
        };

        $useCase = new ExploreCareerPaths($repo, $this->mapper);
        $results = $useCase->execute();

        $this->assertCount(1, $results);
        $this->assertInstanceOf(CareerPathDto::class, $results[0]);
        $this->assertSame('Software Engineer', $results[0]->title);
        $this->assertSame('software_engineer', $results[0]->targetRole);
    }

    public function test_it_gets_career_path_details(): void
    {
        $path = CareerPath::create(
            CareerPathId::generate(),
            'Data Scientist',
            'Data science career path',
            'data_scientist',
            ['Python', 'SQL', 'Statistics'],
        );
        $pathId = $path->id()->value();

        $repo = new class($path) implements CareerPathRepositoryInterface
        {
            private CareerPath $path;

            public function __construct(CareerPath $path)
            {
                $this->path = $path;
            }

            public function findById(CareerPathId $id): ?CareerPath
            {
                return $id->equals($this->path->id()) ? $this->path : null;
            }

            public function findAll(): array
            {
                return [];
            }

            public function findByTargetRole(string $targetRole): array
            {
                return [];
            }

            public function save(CareerPath $careerPath): void {}

            public function delete(CareerPathId $id): void {}
        };

        $useCase = new GetCareerPathDetails($repo, $this->mapper);
        $dto = $useCase->execute($pathId);

        $this->assertInstanceOf(CareerPathDto::class, $dto);
        $this->assertSame('Data Scientist', $dto->title);
        $this->assertSame('data_scientist', $dto->targetRole);
        $this->assertSame(['Python', 'SQL', 'Statistics'], $dto->requiredSkills);
    }

    public function test_it_publishes_portfolio(): void
    {
        $studentId = 'student-456';

        $repo = new class implements PublicPortfolioRepositoryInterface
        {
            public ?PublicPortfolio $saved = null;

            public function findById(PublicPortfolioId $id): ?PublicPortfolio
            {
                return null;
            }

            public function findByStudentId(string $studentId): ?PublicPortfolio
            {
                return null;
            }

            public function findBySlug(string $slug): ?PublicPortfolio
            {
                return null;
            }

            public function save(PublicPortfolio $portfolio): void
            {
                $this->saved = $portfolio;
            }

            public function delete(PublicPortfolioId $id): void {}
        };

        $events = new class implements EventDispatcherInterface
        {
            public array $dispatched = [];

            public function dispatch(array $events): void
            {
                $this->dispatched = array_merge($this->dispatched, $events);
            }
        };

        $useCase = new PublishPortfolio($repo, $events, $this->mapper);
        $dto = $useCase->execute($studentId, 'my-portfolio', 'creative', 'My bio', 'My Portfolio');

        $this->assertInstanceOf(PublicPortfolioDto::class, $dto);
        $this->assertSame($studentId, $dto->studentId);
        $this->assertSame('my-portfolio', $dto->slug);
        $this->assertTrue($dto->isActive);
        $this->assertNotNull($repo->saved);
        $this->assertTrue($repo->saved->isActive());
        $this->assertCount(1, $events->dispatched);
    }

    public function test_it_gets_public_portfolio(): void
    {
        $studentId = 'student-789';
        $slug = PortfolioSlug::fromString('my-public-portfolio');

        $portfolio = PublicPortfolio::reconstitute(
            PublicPortfolioId::generate(),
            $studentId,
            $slug,
            'My Portfolio',
            'About me',
            PortfolioTheme::MODERN,
            true,
            10,
            new DateTimeImmutable('-30 days'),
            new DateTimeImmutable('-1 days'),
        );

        $repo = new class($portfolio) implements PublicPortfolioRepositoryInterface
        {
            private PublicPortfolio $portfolio;

            public function __construct(PublicPortfolio $portfolio)
            {
                $this->portfolio = $portfolio;
            }

            public function findById(PublicPortfolioId $id): ?PublicPortfolio
            {
                return null;
            }

            public function findByStudentId(string $studentId): ?PublicPortfolio
            {
                return null;
            }

            public function findBySlug(string $slug): ?PublicPortfolio
            {
                return $this->portfolio->slug()->value() === $slug ? $this->portfolio : null;
            }

            public function save(PublicPortfolio $portfolio): void
            {
                $this->portfolio = $portfolio;
            }

            public function delete(PublicPortfolioId $id): void {}
        };

        $profileGateway = new class implements CareerProfileGatewayInterface
        {
            public function getProfile(string $studentId): ?array
            {
                return ['name' => 'John Doe'];
            }

            public function getPortfolioItems(string $studentId): array
            {
                return [['title' => 'Project A']];
            }

            public function getExperiences(string $studentId): array
            {
                return [['company' => 'Acme Corp']];
            }

            public function getResumes(string $studentId): array
            {
                return [];
            }

            public function getCareerGoals(string $studentId): array
            {
                return [];
            }

            public function getDashboard(string $studentId, ?float $gpa = null): ?array
            {
                return null;
            }
        };

        $useCase = new GetPublicPortfolio($repo, $profileGateway, $this->mapper);
        $result = $useCase->execute('my-public-portfolio');

        $this->assertArrayHasKey('portfolio', $result);
        $this->assertArrayHasKey('profile', $result);
        $this->assertArrayHasKey('portfolio_items', $result);
        $this->assertArrayHasKey('experiences', $result);
        $this->assertInstanceOf(PublicPortfolioDto::class, $result['portfolio']);
        $this->assertSame('My Portfolio', $result['portfolio']->title);
        $this->assertSame(['name' => 'John Doe'], $result['profile']);
    }

    public function test_it_increments_portfolio_views(): void
    {
        $portfolio = PublicPortfolio::reconstitute(
            PublicPortfolioId::generate(),
            'student-101',
            PortfolioSlug::fromString('increment-test'),
            'Test Portfolio',
            null,
            PortfolioTheme::MINIMAL,
            true,
            5,
            new DateTimeImmutable('-10 days'),
            new DateTimeImmutable('-1 days'),
        );

        $repo = new class($portfolio) implements PublicPortfolioRepositoryInterface
        {
            private PublicPortfolio $portfolio;
            public ?PublicPortfolio $saved = null;

            public function __construct(PublicPortfolio $portfolio)
            {
                $this->portfolio = $portfolio;
            }

            public function findById(PublicPortfolioId $id): ?PublicPortfolio
            {
                return null;
            }

            public function findByStudentId(string $studentId): ?PublicPortfolio
            {
                return null;
            }

            public function findBySlug(string $slug): ?PublicPortfolio
            {
                return $this->portfolio->slug()->value() === $slug ? $this->portfolio : null;
            }

            public function save(PublicPortfolio $portfolio): void
            {
                $this->portfolio = $portfolio;
                $this->saved = $portfolio;
            }

            public function delete(PublicPortfolioId $id): void {}
        };

        $useCase = new IncrementPortfolioViews($repo);
        $useCase->execute('increment-test');

        $this->assertNotNull($repo->saved);
        $this->assertSame(6, $repo->saved->viewsCount());
    }

    public function test_it_calculates_employment_readiness(): void
    {
        $studentId = 'student-202';

        $profileGateway = new class implements CareerProfileGatewayInterface
        {
            public function getProfile(string $studentId): ?array
            {
                return ['gpa' => 3.5];
            }

            public function getPortfolioItems(string $studentId): array
            {
                return [];
            }

            public function getExperiences(string $studentId): array
            {
                return [['company' => 'Tech Co', 'role' => 'Intern']];
            }

            public function getResumes(string $studentId): array
            {
                return [];
            }

            public function getCareerGoals(string $studentId): array
            {
                return [['title' => 'Learn Laravel', 'progress' => 50]];
            }

            public function getDashboard(string $studentId, ?float $gpa = null): ?array
            {
                return null;
            }
        };

        $skillsGateway = new class implements SkillsGatewayInterface
        {
            public function getSkillProfile(string $studentId): ?array
            {
                return null;
            }

            public function getSkills(string $studentId): array
            {
                return [
                    ['name' => 'PHP', 'level' => 'advanced'],
                    ['name' => 'Laravel', 'level' => 'intermediate'],
                ];
            }

            public function getCertifications(string $studentId): array
            {
                return [['name' => 'PHP Certification']];
            }

            public function getAchievements(string $studentId): array
            {
                return [];
            }

            public function getLearningPaths(string $studentId): array
            {
                return [];
            }
        };

        $useCase = new CalculateEmploymentReadiness($profileGateway, $skillsGateway);
        $result = $useCase->execute($studentId, 3.5);

        $this->assertArrayHasKey('score', $result);
        $this->assertArrayHasKey('breakdown', $result);
        $this->assertGreaterThan(0, $result['score']);
        $this->assertArrayHasKey('gpa', $result['breakdown']);
        $this->assertArrayHasKey('skills', $result['breakdown']);
        $this->assertArrayHasKey('experience', $result['breakdown']);
        $this->assertArrayHasKey('certifications', $result['breakdown']);
        $this->assertArrayHasKey('goals', $result['breakdown']);
    }

    public function test_it_gets_comprehensive_dashboard(): void
    {
        $studentId = 'student-303';

        $profileGateway = new class implements CareerProfileGatewayInterface
        {
            public function getProfile(string $studentId): ?array
            {
                return null;
            }

            public function getPortfolioItems(string $studentId): array
            {
                return [];
            }

            public function getExperiences(string $studentId): array
            {
                return [];
            }

            public function getResumes(string $studentId): array
            {
                return [];
            }

            public function getCareerGoals(string $studentId): array
            {
                return [];
            }

            public function getDashboard(string $studentId, ?float $gpa = null): ?array
            {
                return null;
            }
        };

        $skillsGateway = new class implements SkillsGatewayInterface
        {
            public function getSkillProfile(string $studentId): ?array
            {
                return null;
            }

            public function getSkills(string $studentId): array
            {
                return [];
            }

            public function getCertifications(string $studentId): array
            {
                return [];
            }

            public function getAchievements(string $studentId): array
            {
                return [];
            }

            public function getLearningPaths(string $studentId): array
            {
                return [];
            }
        };

        $opportunitiesGateway = new class implements OpportunitiesGatewayInterface
        {
            public function getSavedOpportunities(string $studentId): array
            {
                return [];
            }

            public function getApplications(string $studentId): array
            {
                return [];
            }

            public function getRecommendations(string $studentId): array
            {
                return [];
            }

            public function getRecommendedOpportunities(string $studentId, int $limit = 10): array
            {
                return [];
            }
        };

        $interviewRepo = new class implements InterviewRepositoryInterface
        {
            public function findById(InterviewId $id): ?Interview
            {
                return null;
            }

            public function findByStudentId(string $studentId): array
            {
                return [];
            }

            public function save(Interview $interview): void {}

            public function delete(InterviewId $id): void {}
        };

        $careerPathRepo = new class implements CareerPathRepositoryInterface
        {
            public function findById(CareerPathId $id): ?CareerPath
            {
                return null;
            }

            public function findAll(): array
            {
                return [];
            }

            public function findByTargetRole(string $targetRole): array
            {
                return [];
            }

            public function save(CareerPath $careerPath): void {}

            public function delete(CareerPathId $id): void {}
        };

        $readiness = new CalculateEmploymentReadiness($profileGateway, $skillsGateway);

        $useCase = new GetComprehensiveDashboard(
            $profileGateway,
            $skillsGateway,
            $opportunitiesGateway,
            $interviewRepo,
            $careerPathRepo,
            $this->mapper,
            $readiness,
        );

        $dashboard = $useCase->execute($studentId);

        $this->assertInstanceOf(ComprehensiveDashboardDto::class, $dashboard);
        $this->assertNull($dashboard->profile);
        $this->assertNull($dashboard->skillProfile);
        $this->assertIsArray($dashboard->opportunities);
        $this->assertIsArray($dashboard->interviews);
        $this->assertIsArray($dashboard->careerPaths);
        $this->assertIsFloat($dashboard->readinessScore);
    }
}
