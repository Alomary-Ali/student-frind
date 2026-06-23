<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Domain\Enums;

use Modules\Career\Domain\Enums\InterviewStatus;
use Modules\Career\Domain\Enums\InterviewType;
use Modules\Career\Domain\Enums\PortfolioTheme;
use PHPUnit\Framework\TestCase;

final class CareerEnumsTest extends TestCase
{
    public function test_interview_type_values(): void
    {
        $this->assertSame('mock', InterviewType::MOCK->value);
        $this->assertSame('technical', InterviewType::TECHNICAL->value);
        $this->assertSame('behavioral', InterviewType::BEHAVIORAL->value);
        $this->assertSame('general', InterviewType::GENERAL->value);
    }

    public function test_interview_type_labels(): void
    {
        $this->assertSame('مقابلة تجريبية', InterviewType::MOCK->label());
        $this->assertSame('تقنية', InterviewType::TECHNICAL->label());
        $this->assertSame('سلوكية', InterviewType::BEHAVIORAL->label());
        $this->assertSame('عامة', InterviewType::GENERAL->label());
    }

    public function test_interview_status_values(): void
    {
        $this->assertSame('scheduled', InterviewStatus::SCHEDULED->value);
        $this->assertSame('completed', InterviewStatus::COMPLETED->value);
        $this->assertSame('cancelled', InterviewStatus::CANCELLED->value);
    }

    public function test_interview_status_labels(): void
    {
        $this->assertSame('مكتملة', InterviewStatus::COMPLETED->label());
        $this->assertSame('ملغاة', InterviewStatus::CANCELLED->label());
    }

    public function test_portfolio_theme_values(): void
    {
        $this->assertSame('modern', PortfolioTheme::MODERN->value);
        $this->assertSame('creative', PortfolioTheme::CREATIVE->value);
    }

    public function test_portfolio_theme_labels(): void
    {
        $this->assertSame('حديث', PortfolioTheme::MODERN->label());
        $this->assertSame('إبداعي', PortfolioTheme::CREATIVE->label());
    }
}
