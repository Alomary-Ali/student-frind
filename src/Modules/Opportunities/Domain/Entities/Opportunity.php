<?php

declare(strict_types=1);

namespace Modules\Opportunities\Domain\Entities;

use DateTimeImmutable;
use Modules\Opportunities\Domain\Enums\OpportunityStatus;
use Modules\Opportunities\Domain\Enums\OpportunityType;
use Modules\Opportunities\Domain\Enums\Provider;
use Modules\Opportunities\Domain\Events\OpportunityCreated;
use Modules\Opportunities\Domain\Events\OpportunityUpdated;
use Modules\Opportunities\Domain\ValueObjects\OpportunityId;

final class Opportunity
{
    /** @var list<object> */
    private array $domainEvents = [];

    private function __construct(
        private readonly OpportunityId $id,
        private string $title,
        private string $description,
        private Provider $provider,
        private OpportunityType $type,
        private ?string $location,
        private ?string $country,
        private ?DateTimeImmutable $deadline,
        private ?string $applyUrl,
        private OpportunityStatus $status,
        private array $metadata,
        private ?string $sourceUrl,
        private ?string $imageUrl,
        private array $tags,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function createJob(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        string $applyUrl,
        ?string $company,
        ?float $salaryMin,
        ?float $salaryMax,
        ?string $employmentType,
        ?string $locationType,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;
        $opportunity = new self(
            $id,
            $title,
            $description,
            $provider,
            OpportunityType::JOB,
            $location,
            $country,
            $deadline,
            $applyUrl,
            OpportunityStatus::ACTIVE,
            [
                'company' => $company,
                'salary_min' => $salaryMin,
                'salary_max' => $salaryMax,
                'employment_type' => $employmentType,
                'location_type' => $locationType,
            ],
            null,
            null,
            $tags,
            $now,
            $now,
        );

        $opportunity->raise(new OpportunityCreated(
            $id->value(),
            OpportunityType::JOB->value,
            $title,
            $provider->value,
            $now,
        ));

        return $opportunity;
    }

    public static function createInternship(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        string $applyUrl,
        ?string $company,
        ?int $durationMonths,
        bool $isPaid,
        bool $isRemote,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;
        $opportunity = new self(
            $id,
            $title,
            $description,
            $provider,
            OpportunityType::INTERNSHIP,
            $location,
            $country,
            $deadline,
            $applyUrl,
            OpportunityStatus::ACTIVE,
            [
                'company' => $company,
                'duration_months' => $durationMonths,
                'is_paid' => $isPaid,
                'is_remote' => $isRemote,
            ],
            null,
            null,
            $tags,
            $now,
            $now,
        );

        $opportunity->raise(new OpportunityCreated(
            $id->value(),
            OpportunityType::INTERNSHIP->value,
            $title,
            $provider->value,
            $now,
        ));

        return $opportunity;
    }

    public static function createScholarship(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        string $applyUrl,
        ?string $university,
        ?string $programLevel,
        ?float $coverageAmount,
        ?string $coverageCurrency,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;
        $opportunity = new self(
            $id,
            $title,
            $description,
            $provider,
            OpportunityType::SCHOLARSHIP,
            $location,
            $country,
            $deadline,
            $applyUrl,
            OpportunityStatus::ACTIVE,
            [
                'university' => $university,
                'program_level' => $programLevel,
                'coverage_amount' => $coverageAmount,
                'coverage_currency' => $coverageCurrency,
            ],
            null,
            null,
            $tags,
            $now,
            $now,
        );

        $opportunity->raise(new OpportunityCreated(
            $id->value(),
            OpportunityType::SCHOLARSHIP->value,
            $title,
            $provider->value,
            $now,
        ));

        return $opportunity;
    }

    public static function createCourse(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        ?DateTimeImmutable $deadline,
        string $applyUrl,
        ?string $platform,
        ?int $durationHours,
        ?string $providerUrl,
        bool $hasCertificate,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;
        $opportunity = new self(
            $id,
            $title,
            $description,
            $provider,
            OpportunityType::COURSE,
            null,
            null,
            $deadline,
            $applyUrl,
            OpportunityStatus::ACTIVE,
            [
                'platform' => $platform,
                'duration_hours' => $durationHours,
                'provider_url' => $providerUrl,
                'has_certificate' => $hasCertificate,
            ],
            null,
            null,
            $tags,
            $now,
            $now,
        );

        $opportunity->raise(new OpportunityCreated(
            $id->value(),
            OpportunityType::COURSE->value,
            $title,
            $provider->value,
            $now,
        ));

        return $opportunity;
    }

    public static function createCompetition(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        string $applyUrl,
        ?string $organizer,
        ?float $prizeAmount,
        ?int $teamSize,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;
        $opportunity = new self(
            $id,
            $title,
            $description,
            $provider,
            OpportunityType::COMPETITION,
            $location,
            $country,
            $deadline,
            $applyUrl,
            OpportunityStatus::ACTIVE,
            [
                'organizer' => $organizer,
                'prize_amount' => $prizeAmount,
                'team_size' => $teamSize,
            ],
            null,
            null,
            $tags,
            $now,
            $now,
        );

        $opportunity->raise(new OpportunityCreated(
            $id->value(),
            OpportunityType::COMPETITION->value,
            $title,
            $provider->value,
            $now,
        ));

        return $opportunity;
    }

    public static function createVolunteering(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        string $applyUrl,
        ?string $organization,
        ?int $durationWeeks,
        bool $isVirtual,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;
        $opportunity = new self(
            $id,
            $title,
            $description,
            $provider,
            OpportunityType::VOLUNTEERING,
            $location,
            $country,
            $deadline,
            $applyUrl,
            OpportunityStatus::ACTIVE,
            [
                'organization' => $organization,
                'duration_weeks' => $durationWeeks,
                'is_virtual' => $isVirtual,
            ],
            null,
            null,
            $tags,
            $now,
            $now,
        );

        $opportunity->raise(new OpportunityCreated(
            $id->value(),
            OpportunityType::VOLUNTEERING->value,
            $title,
            $provider->value,
            $now,
        ));

        return $opportunity;
    }

    public static function createConference(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        string $applyUrl,
        ?string $organizer,
        ?DateTimeImmutable $eventDate,
        ?string $venue,
        ?DateTimeImmutable $cfpDeadline,
        array $tags = [],
    ): self {
        $now = new DateTimeImmutable;
        $opportunity = new self(
            $id,
            $title,
            $description,
            $provider,
            OpportunityType::CONFERENCE,
            $location,
            $country,
            $deadline,
            $applyUrl,
            OpportunityStatus::ACTIVE,
            [
                'organizer' => $organizer,
                'event_date' => $eventDate?->format('c'),
                'venue' => $venue,
                'cfp_deadline' => $cfpDeadline?->format('c'),
            ],
            null,
            null,
            $tags,
            $now,
            $now,
        );

        $opportunity->raise(new OpportunityCreated(
            $id->value(),
            OpportunityType::CONFERENCE->value,
            $title,
            $provider->value,
            $now,
        ));

        return $opportunity;
    }

    public static function reconstitute(
        OpportunityId $id,
        string $title,
        string $description,
        Provider $provider,
        OpportunityType $type,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        ?string $applyUrl,
        OpportunityStatus $status,
        array $metadata,
        ?string $sourceUrl,
        ?string $imageUrl,
        array $tags,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $title,
            $description,
            $provider,
            $type,
            $location,
            $country,
            $deadline,
            $applyUrl,
            $status,
            $metadata,
            $sourceUrl,
            $imageUrl,
            $tags,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): OpportunityId
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function provider(): Provider
    {
        return $this->provider;
    }

    public function type(): OpportunityType
    {
        return $this->type;
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function country(): ?string
    {
        return $this->country;
    }

    public function deadline(): ?DateTimeImmutable
    {
        return $this->deadline;
    }

    public function applyUrl(): ?string
    {
        return $this->applyUrl;
    }

    public function status(): OpportunityStatus
    {
        return $this->status;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function sourceUrl(): ?string
    {
        return $this->sourceUrl;
    }

    public function imageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function tags(): array
    {
        return $this->tags;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function close(): void
    {
        $this->status = OpportunityStatus::CLOSED;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function publish(): void
    {
        $this->status = OpportunityStatus::ACTIVE;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function updateDetails(
        string $title,
        string $description,
        ?string $location,
        ?string $country,
        ?DateTimeImmutable $deadline,
        ?string $applyUrl,
        array $metadata,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->country = $country;
        $this->deadline = $deadline;
        $this->applyUrl = $applyUrl;
        $this->metadata = $metadata;
        $this->updatedAt = new DateTimeImmutable;

        $this->raise(new OpportunityUpdated(
            $this->id->value(),
            $this->type->value,
            $this->title,
            $this->updatedAt,
        ));
    }

    public function hasDeadlinePassed(): bool
    {
        return $this->deadline !== null && $this->deadline < new DateTimeImmutable;
    }

    public function isExpired(): bool
    {
        return $this->status === OpportunityStatus::CLOSED || $this->hasDeadlinePassed();
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
