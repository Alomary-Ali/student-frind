<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Skills\Infrastructure\Persistence\Eloquent\EloquentCertification;

/**
 * @extends Factory<EloquentCertification>
 */
final class CertificationFactory extends Factory
{
    protected $model = EloquentCertification::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'skill_profile_id' => null,
            'name' => fake()->randomElement([
                'CCNA', 'AWS Solutions Architect', 'PMP', 'IELTS',
                'TOEFL', 'مشروع إدارة الاحترافية', 'Oracle Java',
                'Google Data Analytics', 'Microsoft Azure Fundamentals',
            ]),
            'issuer' => fake()->randomElement(['Cisco', 'Amazon', 'PMI', 'British Council', 'ETS', 'Oracle', 'Google', 'Microsoft']),
            'issue_date' => fake()->dateTimeBetween('-5 years', '-1 month'),
            'expiry_date' => fake()->boolean(40) ? fake()->dateTimeBetween('+1 month', '+3 years') : null,
            'credential_url' => fake()->boolean(60) ? fake()->url() : null,
            'verification_code' => strtoupper(Str::random(12)),
        ];
    }

    public function withSkillProfileId(string $id): static
    {
        return $this->state(fn (array $attrs) => ['skill_profile_id' => $id]);
    }
}
