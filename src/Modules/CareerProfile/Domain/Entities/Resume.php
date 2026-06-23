<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Domain\Entities;

use DateTimeImmutable;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\ValueObjects\CareerProfileId;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;

final class Resume
{
    private function __construct(
        private readonly ResumeId $id,
        private readonly CareerProfileId $careerProfileId,
        private ResumeTemplate $template,
        private string $content,
        private DateTimeImmutable $generatedAt,
    ) {}

    public static function create(
        ResumeId $id,
        CareerProfileId $careerProfileId,
        ResumeTemplate $template,
        string $content,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $template,
            $content,
            new DateTimeImmutable()
        );
    }

    public static function reconstitute(
        ResumeId $id,
        CareerProfileId $careerProfileId,
        ResumeTemplate $template,
        string $content,
        DateTimeImmutable $generatedAt,
    ): self {
        return new self(
            $id,
            $careerProfileId,
            $template,
            $content,
            $generatedAt
        );
    }

    public function id(): ResumeId
    {
        return $this->id;
    }

    public function careerProfileId(): CareerProfileId
    {
        return $this->careerProfileId;
    }

    public function template(): ResumeTemplate
    {
        return $this->template;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function generatedAt(): DateTimeImmutable
    {
        return $this->generatedAt;
    }

    public function updateContent(string $content): void
    {
        $this->content = $content;
        $this->generatedAt = new DateTimeImmutable();
    }

    public function changeTemplate(ResumeTemplate $template): void
    {
        $this->template = $template;
        $this->generatedAt = new DateTimeImmutable();
    }
}
