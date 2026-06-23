<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Domain\Entities;

use Modules\Career\Domain\Entities\CareerPathStage;
use Modules\Career\Domain\ValueObjects\CareerPathStageId;
use PHPUnit\Framework\TestCase;

final class CareerPathStageEntityTest extends TestCase
{
    public function test_create_returns_stage(): void
    {
        $id = CareerPathStageId::generate();

        $stage = CareerPathStage::create($id, 'مبتدئ', 1, ['HTML', 'CSS'], 6, '10,000-15,000');

        $this->assertSame($id, $stage->id());
        $this->assertSame('مبتدئ', $stage->title());
        $this->assertSame(1, $stage->order());
        $this->assertSame(['HTML', 'CSS'], $stage->requiredSkills());
        $this->assertSame(6, $stage->durationMonths());
        $this->assertSame('10,000-15,000', $stage->salaryRange());
    }

    public function test_update_changes_properties(): void
    {
        $stage = CareerPathStage::create(CareerPathStageId::generate(), 'مبتدئ', 1, ['HTML'], 6);

        $stage->update('متوسط', 2, ['JavaScript', 'React'], 12, '15,000-20,000', 'وصف جديد');

        $this->assertSame('متوسط', $stage->title());
        $this->assertSame(2, $stage->order());
        $this->assertSame(['JavaScript', 'React'], $stage->requiredSkills());
        $this->assertSame(12, $stage->durationMonths());
        $this->assertSame('15,000-20,000', $stage->salaryRange());
        $this->assertSame('وصف جديد', $stage->description());
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = CareerPathStageId::generate();

        $stage = CareerPathStage::reconstitute($id, 'مبتدئ', 1, ['HTML'], 6, '10k', 'وصف');

        $this->assertSame($id->value(), $stage->id()->value());
        $this->assertSame('مبتدئ', $stage->title());
        $this->assertSame('وصف', $stage->description());
    }
}
