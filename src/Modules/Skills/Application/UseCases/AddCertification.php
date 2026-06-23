<?php

declare(strict_types=1);

namespace Modules\Skills\Application\UseCases;

use DateTimeImmutable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Domain\ValueObjects\StudentId;
use Modules\Shared\Domain\Contracts\EventDispatcherInterface;
use Modules\Skills\Application\DTOs\SkillProfileDto;
use Modules\Skills\Application\Mappers\SkillsMapper;
use Modules\Skills\Domain\Contracts\SkillProfileRepositoryInterface;
use Modules\Skills\Domain\Entities\SkillProfile;
use Modules\Skills\Domain\ValueObjects\CertificationId;
use Modules\Skills\Domain\ValueObjects\SkillProfileId;

final readonly class AddCertification
{
    public function __construct(
        private SkillProfileRepositoryInterface $profiles,
        private EventDispatcherInterface $events,
        private SkillsMapper $mapper,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     *
     * @throws ValidationException
     */
    public function execute(string $studentId, array $data): SkillProfileDto
    {
        $validated = Validator::make($data, [
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'credential_url' => 'nullable|url|max:500',
            'verification_code' => 'nullable|string|max:100',
        ])->validate();

        $sid = StudentId::of($studentId);
        $profile = $this->profiles->findByStudentId($sid);

        if ($profile === null) {
            $profile = SkillProfile::create(
                id: SkillProfileId::generate(),
                studentId: $sid,
            );
        }

        $profile->addCertification(
            id: CertificationId::generate(),
            name: $validated['name'],
            issuer: $validated['issuer'],
            issueDate: new DateTimeImmutable($validated['issue_date']),
            expiryDate: isset($validated['expiry_date']) ? new DateTimeImmutable($validated['expiry_date']) : null,
            credentialUrl: $validated['credential_url'] ?? null,
            verificationCode: $validated['verification_code'] ?? null,
        );

        $this->profiles->save($profile);
        $this->events->dispatch($profile->releaseEvents());

        return $this->mapper->toSkillProfileDto($profile);
    }
}
