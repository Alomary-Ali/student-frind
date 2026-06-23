<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentOpportunityApplication;

/**
 * @extends Factory<EloquentOpportunityApplication>
 */
final class OpportunityApplicationFactory extends Factory
{
    protected $model = EloquentOpportunityApplication::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'opportunity_id' => null,
            'student_id' => null,
            'application_status' => fake()->randomElement(['saved', 'applied', 'in_review', 'accepted', 'rejected']),
            'applied_at' => fake()->dateTimeThisMonth(),
            'notes' => fake('ar_SA')->optional()->sentence(),
        ];
    }

    public function applied(): static
    {
        return $this->state(fn (array $attrs) => ['application_status' => 'applied', 'applied_at' => now()]);
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attrs) => ['application_status' => 'accepted']);
    }
}
