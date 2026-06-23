<?php

declare(strict_types=1);

namespace Modules\Productivity\Tests\Unit\Domain\ValueObjects;

use Modules\Productivity\Domain\Exceptions\InvalidPriorityLevelException;
use Modules\Productivity\Domain\ValueObjects\PriorityLevel;
use PHPUnit\Framework\TestCase;

final class PriorityLevelTest extends TestCase
{
    public function test_priority_levels_can_be_created(): void
    {
        $low = PriorityLevel::low();
        $medium = PriorityLevel::medium();
        $high = PriorityLevel::high();
        $urgent = PriorityLevel::urgent();

        $this->assertTrue($low->isLow());
        $this->assertTrue($medium->isMedium());
        $this->assertTrue($high->isHigh());
        $this->assertTrue($urgent->isUrgent());
    }

    public function test_priority_level_can_be_created_from_string(): void
    {
        $priority = PriorityLevel::fromString('high');

        $this->assertTrue($priority->isHigh());
    }

    public function test_invalid_priority_level_throws_exception(): void
    {
        $this->expectException(InvalidPriorityLevelException::class);
        PriorityLevel::fromString('invalid');
    }

    public function test_priority_level_equality(): void
    {
        $priority1 = PriorityLevel::high();
        $priority2 = PriorityLevel::high();
        $priority3 = PriorityLevel::medium();

        $this->assertTrue($priority1->equals($priority2));
        $this->assertFalse($priority1->equals($priority3));
    }

    public function test_priority_level_weight(): void
    {
        $this->assertSame(1, PriorityLevel::low()->weight());
        $this->assertSame(2, PriorityLevel::medium()->weight());
        $this->assertSame(3, PriorityLevel::high()->weight());
        $this->assertSame(4, PriorityLevel::urgent()->weight());
    }
}
