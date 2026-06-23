<?php

declare(strict_types=1);

namespace Modules\Career\Tests\Unit\Domain\Entities;

use Modules\Career\Domain\Entities\CareerPath;
use Modules\Career\Domain\Entities\CareerPathStage;
use Modules\Career\Domain\Events\CareerPathCreated;
use Modules\Career\Domain\ValueObjects\CareerPathId;
use Modules\Career\Domain\ValueObjects\CareerPathStageId;
use PHPUnit\Framework\TestCase;

final class CareerPathEntityTest extends TestCase
{
    public function test_create_returns_career_path(): void
    {
        $id = CareerPathId::generate();

        $path = CareerPath::create(
            $id,
            'مطور ويب',
            'مسار تطوير الويب الشامل',
            'Full-Stack Developer',
            ['PHP', 'JavaScript', 'HTML', 'CSS'],
        );

        $this->assertSame($id, $path->id());
        $this->assertSame('مطور ويب', $path->title());
        $this->assertSame('Full-Stack Developer', $path->targetRole());
        $this->assertCount(0, $path->stages());
    }

    public function test_create_dispatches_career_path_created_event(): void
    {
        $path = CareerPath::create(CareerPathId::generate(), 'مطور ويب', '', 'Web Developer', []);

        $events = $path->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(CareerPathCreated::class, $events[0]);
    }

    public function test_add_stage_appends_to_stages(): void
    {
        $path = CareerPath::create(CareerPathId::generate(), 'مطور ويب', '', 'Web Developer', []);
        $path->releaseEvents();

        $stage = CareerPathStage::create(CareerPathStageId::generate(), 'مبتدئ', 1, ['HTML', 'CSS'], 6);
        $path->addStage($stage);

        $this->assertCount(1, $path->stages());
        $this->assertSame('مبتدئ', $path->stages()[0]->title());
    }

    public function test_get_total_duration_sums_all_stages(): void
    {
        $path = CareerPath::create(CareerPathId::generate(), 'مطور ويب', '', 'Web Developer', []);
        $path->releaseEvents();

        $path->addStage(CareerPathStage::create(CareerPathStageId::generate(), 'مبتدئ', 1, [], 6));
        $path->addStage(CareerPathStage::create(CareerPathStageId::generate(), 'متوسط', 2, [], 12));
        $path->addStage(CareerPathStage::create(CareerPathStageId::generate(), 'متقدم', 3, [], 18));

        $this->assertSame(36, $path->getTotalDuration());
    }

    public function test_get_total_duration_returns_zero_with_no_stages(): void
    {
        $path = CareerPath::create(CareerPathId::generate(), 'مطور ويب', '', 'Web Developer', []);

        $this->assertSame(0, $path->getTotalDuration());
    }

    public function test_get_all_required_skills_merges_path_and_stage_skills(): void
    {
        $path = CareerPath::create(CareerPathId::generate(), 'مطور ويب', '', 'Web Developer', ['PHP', 'JavaScript']);
        $path->releaseEvents();

        $path->addStage(CareerPathStage::create(CareerPathStageId::generate(), 'مبتدئ', 1, ['HTML', 'CSS'], 6));
        $path->addStage(CareerPathStage::create(CareerPathStageId::generate(), 'متوسط', 2, ['JavaScript', 'React'], 12));

        $skills = $path->getAllRequiredSkills();
        $this->assertContains('PHP', $skills);
        $this->assertContains('JavaScript', $skills);
        $this->assertContains('HTML', $skills);
    }

    public function test_reconstitute_restores_entity(): void
    {
        $id = CareerPathId::generate();

        $path = CareerPath::reconstitute(
            $id,
            'مطور ويب',
            'وصف',
            'Web Developer',
            ['PHP'],
            [],
            '80,000',
            '10%',
            new \DateTimeImmutable,
            new \DateTimeImmutable,
        );

        $this->assertSame($id->value(), $path->id()->value());
        $this->assertSame('مطور ويب', $path->title());
        $this->assertSame('80,000', $path->averageSalary());
    }

    public function test_update_description(): void
    {
        $path = CareerPath::create(CareerPathId::generate(), 'مطور ويب', 'قديم', 'Web Developer', []);

        $path->updateDescription('جديد');

        $this->assertSame('جديد', $path->description());
    }
}
