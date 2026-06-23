<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Application\UseCases;

use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\CareerProfile\Application\DTOs\ResumeDto;
use Modules\CareerProfile\Application\Mappers\CareerProfileMapper;
use Modules\CareerProfile\Domain\Contracts\CareerProfileRepositoryInterface;
use Modules\CareerProfile\Domain\Enums\ResumeTemplate;
use Modules\CareerProfile\Domain\Services\ResumeGenerator;
use Modules\CareerProfile\Domain\ValueObjects\ResumeId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;

final readonly class GenerateResume
{
    public function __construct(
        private CareerProfileRepositoryInterface $profiles,
        private SkillProfileRepositoryInterface $skillProfiles,
        private ResumeGenerator $generator,
        private EventDispatcherInterface $events,
        private CareerProfileMapper $mapper,
    ) {}

    public function execute(string $studentId, string $template, string $studentName, string $studentEmail): ResumeDto
    {
        $profile = $this->profiles->findByStudentId(StudentId::of($studentId));

        if ($profile === null) {
            throw new \RuntimeException("Career profile not found for student: {$studentId}");
        }

        $skillProfile = $this->skillProfiles->findByStudentId(StudentId::of($studentId));

        $content = $this->generator->generate(
            profile: $profile,
            skillProfile: $skillProfile,
            studentName: $studentName,
            studentEmail: $studentEmail,
        );

        $resumeTemplate = ResumeTemplate::from($template);

        $profile->generateResume(
            id: ResumeId::generate(),
            template: $resumeTemplate,
            content: $content,
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        $resumes = $profile->resumes();
        $resume = end($resumes);

        return $this->mapper->toResumeDto($resume);
    }
}
