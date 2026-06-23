<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Skills\Application\DTOs\LearningPathDto;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Domain\Contracts\LearningPathRepositoryInterface;
use Modules\Skills\Domain\ValueObjects\LearningPathId;

final readonly class UpdateLearningPathProgress
{
    public function __construct(
        private LearningPathRepositoryInterface $paths,
        private EventDispatcherInterface $events,
        private SkillsMapper $mapper,
    ) {}

    public function execute(string $learningPathId, ?string $completeStepId = null, ?int $setProgress = null): LearningPathDto
    {
        $id = LearningPathId::of($learningPathId);
        $path = $this->paths->findById($id);

        if ($path === null) {
            throw new \RuntimeException('Learning path not found.');
        }

        if ($completeStepId !== null) {
            $path->completeStep($completeStepId);
        }

        if ($setProgress !== null) {
            $path->updateProgress($setProgress);
        }

        $this->paths->save($path);

        return $this->mapper->toLearningPathDto($path);
    }
}
