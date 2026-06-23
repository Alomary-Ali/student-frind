<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Services;

use Modules\Opportunities\Domain\Entities\Opportunity;
use Modules\Opportunities\Domain\ValueObjects\OpportunityScore;

final class RecommendationEngine
{
    private const WEIGHT_MAJOR = 40.0;
    private const WEIGHT_SKILLS = 30.0;
    private const WEIGHT_CAREER_SCORE = 15.0;
    private const WEIGHT_GPA = 10.0;
    private const WEIGHT_INTERESTS = 5.0;

    /**
     * @param  array<Opportunity>  $opportunities
     * @param  array<string>  $skills
     * @param  array<string>  $interests
     * @return list<array{opportunity: Opportunity, score: OpportunityScore, reason: string}>
     */
    public function rank(
        string $studentId,
        array $opportunities,
        ?float $gpa = null,
        ?string $major = null,
        ?float $careerScore = null,
        array $skills = [],
        array $interests = [],
    ): array {
        $results = [];

        foreach ($opportunities as $opportunity) {
            $score = 0.0;
            $reasons = [];

            $majorScore = $this->calculateMajorScore($major, $opportunity);
            $score += $majorScore;
            if ($majorScore > 0) {
                $reasons[] = 'تطابق مع التخصص';
            }

            $skillsScore = $this->calculateSkillsScore($skills, $opportunity);
            $score += $skillsScore;
            if ($skillsScore > 0) {
                $reasons[] = 'مهارات مناسبة';
            }

            $careerScoreVal = $this->calculateCareerScore($careerScore);
            $score += $careerScoreVal;
            if ($careerScoreVal > 0) {
                $reasons[] = 'جاهزية مهنية جيدة';
            }

            $gpaScore = $this->calculateGpaScore($gpa);
            $score += $gpaScore;
            if ($gpaScore > 0) {
                $reasons[] = 'معدل أكاديمي مناسب';
            }

            $interestScore = $this->calculateInterestScore($interests, $opportunity);
            $score += $interestScore;
            if ($interestScore > 0) {
                $reasons[] = 'يتوافق مع اهتماماتك';
            }

            $finalScore = OpportunityScore::fromFloat($score);
            $reason = ! empty($reasons) ? implode('، ', $reasons) : null;

            $results[] = [
                'opportunity' => $opportunity,
                'score' => $finalScore,
                'reason' => $reason,
            ];
        }

        usort($results, fn (array $a, array $b) => $b['score']->value() <=> $a['score']->value());

        return $results;
    }

    private function calculateMajorScore(?string $major, Opportunity $opportunity): float
    {
        if ($major === null || $major === '') {
            return 0;
        }

        $opportunityText = mb_strtolower($opportunity->title() . ' ' . $opportunity->description());
        $majorText = mb_strtolower($major);
        $majorKeywords = explode(' ', $majorText);
        $keywords = array_merge($majorKeywords, [$majorText]);

        foreach ($keywords as $keyword) {
            if (mb_strlen($keyword) < 3) {
                continue;
            }

            if (mb_strpos($opportunityText, $keyword) !== false) {
                return self::WEIGHT_MAJOR;
            }
        }

        $tags = $opportunity->tags();
        foreach ($tags as $tag) {
            if (mb_strpos(mb_strtolower($tag), $majorText) !== false) {
                return self::WEIGHT_MAJOR * 0.75;
            }

            if (similar_text(mb_strtolower($tag), $majorText) > 70) {
                return self::WEIGHT_MAJOR * 0.5;
            }
        }

        return 0;
    }

    private function calculateSkillsScore(array $skills, Opportunity $opportunity): float
    {
        if (empty($skills)) {
            return 0;
        }

        $opportunityText = mb_strtolower($opportunity->title() . ' ' . $opportunity->description());
        $tags = array_map('mb_strtolower', $opportunity->tags());
        $metadata = $opportunity->metadata();

        $requiredSkills = $metadata['required_skills'] ?? [];

        $matchingCount = 0;
        $totalSkills = count($skills);

        foreach ($skills as $skill) {
            $skillLower = mb_strtolower($skill);

            if (in_array($skillLower, $tags, true)) {
                $matchingCount++;
                continue;
            }

            if (mb_strpos($opportunityText, $skillLower) !== false) {
                $matchingCount++;
                continue;
            }

            foreach ($requiredSkills as $required) {
                if (mb_strtolower($required) === $skillLower) {
                    $matchingCount++;
                    break;
                }
            }
        }

        if ($totalSkills === 0) {
            return 0;
        }

        return ($matchingCount / $totalSkills) * self::WEIGHT_SKILLS;
    }

    private function calculateCareerScore(?float $careerScore): float
    {
        if ($careerScore === null) {
            return 0;
        }

        $normalized = min(max($careerScore, 0), 100);

        return ($normalized / 100) * self::WEIGHT_CAREER_SCORE;
    }

    private function calculateGpaScore(?float $gpa): float
    {
        if ($gpa === null) {
            return 0;
        }

        $normalized = min(max($gpa, 0), 4.0);

        return ($normalized / 4.0) * self::WEIGHT_GPA;
    }

    private function calculateInterestScore(array $interests, Opportunity $opportunity): float
    {
        if (empty($interests)) {
            return 0;
        }

        $opportunityText = mb_strtolower($opportunity->title() . ' ' . $opportunity->description());
        $tags = array_map('mb_strtolower', $opportunity->tags());

        foreach ($interests as $interest) {
            $interestLower = mb_strtolower($interest);

            if (in_array($interestLower, $tags, true)) {
                return self::WEIGHT_INTERESTS;
            }

            if (mb_strpos($opportunityText, $interestLower) !== false) {
                return self::WEIGHT_INTERESTS;
            }
        }

        return 0;
    }
}
