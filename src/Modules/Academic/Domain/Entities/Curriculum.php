<?php

declare(strict_types=1);

namespace Modules\Academic\Domain\Entities;

use DateTimeImmutable;
use Modules\Academic\Domain\ValueObjects\CourseId;
use Modules\Academic\Domain\ValueObjects\Credits;
use Modules\Academic\Domain\ValueObjects\CurriculumId;

final class Curriculum
{
    /** @var list<CurriculumCourse> */
    private array $courses = [];

    private function __construct(
        private readonly CurriculumId $id,
        private readonly string $name,
        private readonly string $code,
        private readonly string $description,
        private readonly Credits $totalCreditsRequired,
        private readonly ?string $institutionId,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        CurriculumId $id,
        string $name,
        string $code,
        string $description,
        Credits $totalCreditsRequired,
        ?string $institutionId = null,
    ): self {
        return new self($id, $name, $code, $description, $totalCreditsRequired, $institutionId, new DateTimeImmutable);
    }

    public static function reconstitute(
        CurriculumId $id,
        string $name,
        string $code,
        string $description,
        Credits $totalCreditsRequired,
        ?string $institutionId,
        DateTimeImmutable $createdAt,
        array $courses = [],
    ): self {
        $curriculum = new self($id, $name, $code, $description, $totalCreditsRequired, $institutionId, $createdAt);
        $curriculum->courses = $courses;

        return $curriculum;
    }

    public function addCourse(CourseId $courseId, bool $isRequired, int $semesterOrder): void
    {
        $this->courses[] = new CurriculumCourse($courseId, $isRequired, $semesterOrder);
    }

    /** @return list<CurriculumCourse> */
    public function courses(): array
    {
        return $this->courses;
    }

    public function id(): CurriculumId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function totalCreditsRequired(): Credits
    {
        return $this->totalCreditsRequired;
    }

    public function institutionId(): ?string
    {
        return $this->institutionId;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
