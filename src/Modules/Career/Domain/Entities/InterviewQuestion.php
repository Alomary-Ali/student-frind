<?php

declare(strict_types=1);

namespace Modules\Career\Domain\Entities;

use Modules\Career\Domain\ValueObjects\InterviewQuestionId;

final class InterviewQuestion
{
    private function __construct(
        private readonly InterviewQuestionId $id,
        private string $interviewId,
        private string $question,
        private ?string $category,
        private int $order,
    ) {}

    public static function create(
        InterviewQuestionId $id,
        string $interviewId,
        string $question,
        ?string $category,
        int $order = 0,
    ): self {
        return new self($id, $interviewId, $question, $category, $order);
    }

    public static function reconstitute(
        InterviewQuestionId $id,
        string $interviewId,
        string $question,
        ?string $category,
        int $order,
    ): self {
        return new self($id, $interviewId, $question, $category, $order);
    }

    public function id(): InterviewQuestionId
    {
        return $this->id;
    }

    public function interviewId(): string
    {
        return $this->interviewId;
    }

    public function question(): string
    {
        return $this->question;
    }

    public function category(): ?string
    {
        return $this->category;
    }

    public function order(): int
    {
        return $this->order;
    }
}
