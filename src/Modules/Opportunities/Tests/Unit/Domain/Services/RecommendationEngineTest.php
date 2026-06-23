<?php

declare(strict_types=1);

namespace Modules\Opportunities\Tests\Unit\Domain\Services;

use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\Enums\OpportunityStatus;
use Modules\Opportunities\Domain\Enums\OpportunityType;
use Modules\Opportunities\Domain\Enums\Provider;
use Modules\Opportunities\Domain\Services\RecommendationEngine;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;
use PHPUnit\Framework\TestCase;

final class RecommendationEngineTest extends TestCase
{
    private RecommendationEngine $engine;
    private array $opportunities;

    protected function setUp(): void
    {
        $this->engine = new RecommendationEngine;
        $this->opportunities = [
            $this->createOpportunity('فرصة تطوير ويب', OpportunityType::JOB, ['تطوير', 'ويب', 'Laravel']),
            $this->createOpportunity('منحة ذكاء اصطناعي', OpportunityType::SCHOLARSHIP, ['منحة', 'AI', 'ذكاء اصطناعي']),
            $this->createOpportunity('دورة تصميم', OpportunityType::COURSE, ['تصميم', 'UI', 'UX']),
        ];
    }

    public function test_returns_empty_for_no_opportunities(): void
    {
        $result = $this->engine->rank('student-1', []);

        $this->assertEmpty($result);
    }

    public function test_ranks_based_on_major_match(): void
    {
        $result = $this->engine->rank(
            studentId: 'student-1',
            opportunities: $this->opportunities,
            gpa: null,
            major: 'تطوير',
            careerScore: null,
            skills: [],
            interests: [],
        );

        $this->assertNotEmpty($result);
        $this->assertGreaterThan(0, $result[0]['score']->value());
    }

    public function test_ranks_based_on_skills_match(): void
    {
        $result = $this->engine->rank(
            studentId: 'student-1',
            opportunities: $this->opportunities,
            gpa: null,
            major: null,
            careerScore: null,
            skills: ['Laravel', 'PHP', 'تطوير'],
            interests: [],
        );

        $this->assertNotEmpty($result);
        $scores = array_map(fn ($r) => $r['score']->value(), $result);
        $this->assertGreaterThan($scores[1], $scores[0]);
    }

    public function test_high_gpa_increases_score(): void
    {
        $withoutGpa = $this->engine->rank('student-1', $this->opportunities, null, 'تطوير', null, [], []);
        $withHighGpa = $this->engine->rank('student-1', $this->opportunities, 3.9, 'تطوير', null, [], []);

        $this->assertGreaterThan($withoutGpa[0]['score']->value(), $withHighGpa[0]['score']->value());
    }

    public function test_high_career_score_increases_total(): void
    {
        $withLowCareer = $this->engine->rank('student-1', $this->opportunities, null, null, 20, [], []);
        $withHighCareer = $this->engine->rank('student-1', $this->opportunities, null, null, 90, [], []);

        $this->assertGreaterThan($withLowCareer[0]['score']->value(), $withHighCareer[0]['score']->value());
    }

    public function test_interest_match_adds_score(): void
    {
        $result = $this->engine->rank(
            studentId: 'student-1',
            opportunities: $this->opportunities,
            gpa: null,
            major: null,
            careerScore: null,
            skills: [],
            interests: ['AI', 'ذكاء اصطناعي'],
        );

        $scholarshipResult = null;
        foreach ($result as $r) {
            if ($r['opportunity']->type()->value === 'scholarship') {
                $scholarshipResult = $r;
                break;
            }
        }

        $this->assertNotNull($scholarshipResult);
        $this->assertGreaterThan(0, $scholarshipResult['score']->value());
    }

    public function test_reason_is_provided_when_score_above_zero(): void
    {
        $result = $this->engine->rank(
            studentId: 'student-1',
            opportunities: $this->opportunities,
            gpa: null,
            major: 'تطوير',
            careerScore: null,
            skills: ['Laravel'],
            interests: [],
        );

        $this->assertNotNull($result[0]['reason']);
    }

    public function test_reason_is_null_when_no_match(): void
    {
        $result = $this->engine->rank(
            studentId: 'student-1',
            opportunities: $this->opportunities,
            gpa: null,
            major: null,
            careerScore: null,
            skills: [],
            interests: [],
        );

        $this->assertNull($result[0]['reason']);
    }

    public function test_returns_sorted_by_score_descending(): void
    {
        $result = $this->engine->rank(
            studentId: 'student-1',
            opportunities: $this->opportunities,
            gpa: null,
            major: 'تطوير',
            careerScore: null,
            skills: ['Laravel', 'PHP', 'تطوير'],
            interests: ['تطوير'],
        );

        for ($i = 0; $i < count($result) - 1; $i++) {
            $this->assertGreaterThanOrEqual($result[$i + 1]['score']->value(), $result[$i]['score']->value());
        }
    }

    private function createOpportunity(string $title, OpportunityType $type, array $tags): Opportunity
    {
        $now = new \DateTimeImmutable;

        return Opportunity::reconstitute(
            id: OpportunityId::generate(),
            title: $title,
            description: 'وصف ' . $title,
            provider: Provider::MANUAL,
            type: $type,
            location: null,
            country: null,
            deadline: null,
            applyUrl: 'https://example.com',
            status: OpportunityStatus::ACTIVE,
            metadata: [],
            sourceUrl: null,
            imageUrl: null,
            tags: $tags,
            createdAt: $now,
            updatedAt: $now,
        );
    }
}
