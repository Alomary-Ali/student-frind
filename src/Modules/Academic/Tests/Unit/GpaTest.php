<?php

declare(strict_types=1);

namespace Modules\Academic\Tests\Unit;

use Modules\Academic\Domain\Enums\GradeLetter;
use Modules\Academic\Domain\Exceptions\InvalidGpaException;
use Modules\Academic\Domain\ValueObjects\Gpa;
use Modules\Academic\Domain\ValueObjects\Grade;
use PHPUnit\Framework\TestCase;

final class GpaTest extends TestCase
{
    public function test_gpa_can_be_created_within_valid_range(): void
    {
        $gpa = Gpa::of(3.75);

        $this->assertSame(3.75, $gpa->value());
    }

    public function test_gpa_throws_exception_when_out_of_range(): void
    {
        $this->expectException(InvalidGpaException::class);
        Gpa::of(4.5);
    }

    public function test_grade_maps_to_correct_points(): void
    {
        $grade = Grade::fromLetter(GradeLetter::A);

        $this->assertSame('A', $grade->letterValue());
        $this->assertSame(4.0, $grade->gradePoints());
        $this->assertTrue($grade->isPassing());
    }
}
