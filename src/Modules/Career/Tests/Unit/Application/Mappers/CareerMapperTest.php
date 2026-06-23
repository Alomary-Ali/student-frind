<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Application\Mappers;

use Modules\Career\Application\DTOs\CareerPathDto;
use Modules\Career\Application\DTOs\InterviewDto;
use Modules\Career\Application\DTOs\PublicPortfolioDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Entities\CareerPath;
use Modules\Career\Domain\Entities\CareerPathStage;
use Modules\Career\Domain\Entities\Interview;
use Modules\Career\Domain\Entities\PublicPortfolio;
use Modules\Career\Domain\Enums\InterviewType;
use Modules\Career\Domain\Enums\PortfolioTheme;
use Modules\Career\Domain\ValueObjects\CareerPathId;
use Modules\Career\Domain\ValueObjects\CareerPathStageId;
use Modules\Career\Domain\ValueObjects\InterviewId;
use Modules\Career\Domain\ValueObjects\PortfolioSlug;
use Modules\Career\Domain\ValueObjects\PublicPortfolioId;
use PHPUnit\Framework\TestCase;

final class CareerMapperTest extends TestCase
{
    private CareerMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new CareerMapper;
    }

    public function test_to_interview_dto(): void
    {
        $interview = Interview::create(
            InterviewId::generate(),
            'student-1',
            InterviewType::MOCK,
            new \DateTimeImmutable('2026-07-15 10:00:00'),
        );

        $dto = $this->mapper->toInterviewDto($interview);

        $this->assertInstanceOf(InterviewDto::class, $dto);
        $this->assertSame($interview->id()->value(), $dto->id);
        $this->assertSame('mock', $dto->type);
        $this->assertSame('scheduled', $dto->status);
    }

    public function test_to_career_path_dto(): void
    {
        $path = CareerPath::create(
            CareerPathId::generate(),
            'مطور ويب',
            'وصف المسار',
            'Web Developer',
            ['PHP', 'JavaScript'],
        );
        $path->releaseEvents();
        $path->addStage(CareerPathStage::create(CareerPathStageId::generate(), 'مبتدئ', 1, ['HTML'], 6));

        $dto = $this->mapper->toCareerPathDto($path);

        $this->assertInstanceOf(CareerPathDto::class, $dto);
        $this->assertSame('مطور ويب', $dto->title);
        $this->assertCount(1, $dto->stages);
    }

    public function test_to_public_portfolio_dto(): void
    {
        $portfolio = PublicPortfolio::create(
            PublicPortfolioId::generate(),
            'student-1',
            PortfolioSlug::fromString('my-portfolio'),
            'معرضي',
        );

        $dto = $this->mapper->toPublicPortfolioDto($portfolio);

        $this->assertInstanceOf(PublicPortfolioDto::class, $dto);
        $this->assertSame('my-portfolio', $dto->slug);
        $this->assertFalse($dto->isActive);
    }

    public function test_to_public_portfolio_dto_with_published(): void
    {
        $portfolio = PublicPortfolio::create(
            PublicPortfolioId::generate(),
            'student-1',
            PortfolioSlug::fromString('my-portfolio'),
            'معرضي',
        );
        $portfolio->publish();
        $portfolio->incrementViews();
        $portfolio->updateTheme(PortfolioTheme::CREATIVE);

        $dto = $this->mapper->toPublicPortfolioDto($portfolio);

        $this->assertTrue($dto->isActive);
        $this->assertSame(1, $dto->viewsCount);
        $this->assertSame('creative', $dto->theme);
    }

    public function test_to_interview_question_dto(): void
    {
        $dto = $this->mapper->toInterviewQuestionDto([
            'id' => 'q-1',
            'interview_id' => 'int-1',
            'question' => 'ما هو REST?',
            'category' => 'technical',
            'order' => 1,
        ]);

        $this->assertSame('q-1', $dto->id);
        $this->assertSame('ما هو REST?', $dto->question);
    }

    public function test_to_interview_attempt_dto(): void
    {
        $dto = $this->mapper->toInterviewAttemptDto([
            'id' => 'a-1',
            'interview_id' => 'int-1',
            'student_id' => 'stu-1',
            'answers' => [['q-1' => 'Answer']],
            'score' => 85,
            'submitted_at' => '2026-07-15 10:00:00',
        ]);

        $this->assertSame('a-1', $dto->id);
        $this->assertSame(85, $dto->score);
    }
}
