<?php

declare(strict_types=1);

namespace Modules\Career\Application\UseCases;

use Modules\Career\Application\DTOs\InterviewAttemptDto;
use Modules\Career\Application\Mappers\CareerMapper;
use Modules\Career\Domain\Contracts\InterviewRepositoryInterface;
use Modules\Career\Domain\ValueObjects\InterviewId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;

final readonly class SubmitInterviewAttempt
{
    public function __construct(
        private InterviewRepositoryInterface $interviews,
        private EventDispatcherInterface $events,
        private CareerMapper $mapper,
    ) {}

    public function execute(string $interviewId, string $studentId, array $answers): InterviewAttemptDto
    {
        $interview = $this->interviews->findById(InterviewId::fromString($interviewId));

        $questions = $interview->questions();
        $total = count($questions);
        $correct = 0;

        foreach ($questions as $question) {
            $qId = $question['id'] ?? null;
            if ($qId === null) {
                continue;
            }
            $userAnswer = $answers[$qId] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;
            if ($userAnswer !== null && $correctAnswer !== null && $userAnswer === $correctAnswer) {
                $correct++;
            }
        }

        $score = $total > 0 ? (int) round($correct / $total * 100) : 0;

        $interview->submitAttempt($score);

        $this->interviews->save($interview);
        $this->events->dispatch($interview->releaseEvents());

        return $this->mapper->toInterviewAttemptDto([
            'id' => '',
            'interview_id' => $interviewId,
            'student_id' => $studentId,
            'answers' => $answers,
            'score' => $score,
            'feedback' => $interview->feedback(),
            'submitted_at' => $interview->updatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
