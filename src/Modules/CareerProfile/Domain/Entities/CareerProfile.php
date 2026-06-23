<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Domain\Enums\GoalStatus;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\Events\CareerGoalCompleted;
use Modules\CareerProfile\Domain\Events\CareerGoalCreated;
use Modules\CareerProfile\Domain\Events\CareerProfileCreated;
use Modules\CareerProfile\Domain\Events\ExperienceAdded;
use Modules\CareerProfile\Domain\Events\PortfolioItemAdded;
use Modules\CareerProfile\Domain\Events\ResumeGenerated;
use Modules\CareerProfile\Domain\ValueObjects\CareerGoalId;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ExperienceId;
use Modules\CareerProfile\Domain\ValueObjects\PortfolioItemId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;

final class CareerProfile
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly CareerProfileId $id,
        private readonly StudentId $studentId,
        private string $major,
        private string $summary,
        private array $interests,
        private array $languages,
        private array $portfolioItems,
        private array $experiences,
        private array $resumes,
        private array $careerGoals,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        CareerProfileId $id,
        StudentId $studentId,
        string $major,
        string $summary = '',
        array $interests = [],
        array $languages = [],
    ): self {
        $now = new DateTimeImmutable;
        $profile = new self(
            $id,
            $studentId,
            $major,
            $summary,
            $interests,
            $languages,
            [],
            [],
            [],
            [],
            $now,
            $now,
        );

        $profile->raise(new CareerProfileCreated(
            $id->value(),
            $studentId->value(),
            $major,
            $now,
        ));

        return $profile;
    }

    public static function reconstitute(
        CareerProfileId $id,
        StudentId $studentId,
        string $major,
        string $summary,
        array $interests,
        array $languages,
        array $portfolioItems,
        array $experiences,
        array $resumes,
        array $careerGoals,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $studentId,
            $major,
            $summary,
            $interests,
            $languages,
            $portfolioItems,
            $experiences,
            $resumes,
            $careerGoals,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): CareerProfileId
    {
        return $this->id;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }

    public function major(): string
    {
        return $this->major;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function interests(): array
    {
        return $this->interests;
    }

    public function languages(): array
    {
        return $this->languages;
    }

    /** @return array<PortfolioItem> */
    public function portfolioItems(): array
    {
        return $this->portfolioItems;
    }

    /** @return array<Experience> */
    public function experiences(): array
    {
        return $this->experiences;
    }

    /** @return array<Resume> */
    public function resumes(): array
    {
        return $this->resumes;
    }

    /** @return array<CareerGoal> */
    public function careerGoals(): array
    {
        return $this->careerGoals;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateProfile(string $major, string $summary, array $interests, array $languages): void
    {
        $this->major = $major;
        $this->summary = $summary;
        $this->interests = $interests;
        $this->languages = $languages;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function addPortfolioItem(
        PortfolioItemId $id,
        string $title,
        string $description,
        ?string $projectUrl,
        ?string $githubUrl,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        array $technologies,
    ): void {
        $item = PortfolioItem::create(
            $id,
            $this->id,
            $title,
            $description,
            $projectUrl,
            $githubUrl,
            $startDate,
            $endDate,
            $technologies,
        );
        $this->portfolioItems[] = $item;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new PortfolioItemAdded(
            $id->value(),
            $this->id->value(),
            $title,
            $this->updatedAt,
        ));
    }

    public function addExperience(
        ExperienceId $id,
        string $company,
        string $position,
        string $description,
        DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        bool $isCurrent,
    ): void {
        $experience = Experience::create(
            $id,
            $this->id,
            $company,
            $position,
            $description,
            $startDate,
            $endDate,
            $isCurrent,
        );
        $this->experiences[] = $experience;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new ExperienceAdded(
            $id->value(),
            $this->id->value(),
            $company,
            $position,
            $this->updatedAt,
        ));
    }

    public function generateResume(
        ResumeId $id,
        ResumeTemplate $template,
        string $content,
    ): void {
        $resume = Resume::create(
            $id,
            $this->id,
            $template,
            $content,
        );
        $this->resumes[] = $resume;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new ResumeGenerated(
            $id->value(),
            $this->id->value(),
            $template->value,
            $this->updatedAt,
        ));
    }

    public function createCareerGoal(
        CareerGoalId $id,
        string $title,
        DateTimeImmutable $targetDate,
    ): void {
        $goal = CareerGoal::create(
            $id,
            $this->id,
            $title,
            $targetDate,
        );
        $this->careerGoals[] = $goal;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new CareerGoalCreated(
            $id->value(),
            $this->id->value(),
            $title,
            $this->updatedAt,
        ));
    }

    public function updateCareerGoalProgress(CareerGoalId $goalId, int $progress): void
    {
        foreach ($this->careerGoals as $goal) {
            if ($goal->id()->equals($goalId)) {
                $oldStatus = $goal->status();
                $goal->updateProgress($progress);
                $this->updatedAt = new DateTimeImmutable;

                if ($goal->status() === GoalStatus::COMPLETED && $oldStatus !== GoalStatus::COMPLETED) {
                    $this->raise(new CareerGoalCompleted(
                        $goalId->value(),
                        $this->id->value(),
                        $goal->title(),
                        $this->updatedAt,
                    ));
                }
                break;
            }
        }
    }

    private function raise(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    /** @return list<object> */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
