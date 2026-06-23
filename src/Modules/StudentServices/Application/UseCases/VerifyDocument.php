<?php
declare(strict_types=1);

namespace Modules\StudentServices\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\StudentServices\Domain\Contracts\DocumentRepositoryInterface;

final readonly class VerifyDocument
{
    public function __construct(
        private DocumentRepositoryInterface $documents,
        private EventDispatcherInterface $events,
    ) {}

    public function execute(string $verificationCode): ?array
    {
        $document = $this->documents->findByVerificationCode($verificationCode);

        if ($document === null) {
            return null;
        }

        $document->verify($verificationCode);

        $this->documents->save($document);
        $this->events->dispatch($document->releaseEvents());

        return [
            'id' => $document->id()->value(),
            'student_id' => $document->studentId(),
            'type' => $document->type()->value,
            'title' => $document->title(),
            'status' => $document->status()->value,
            'verification_code' => $document->verificationCode(),
            'verified' => $document->status()->value === 'verified',
            'updated_at' => $document->updatedAt()->format('c'),
        ];
    }
}
