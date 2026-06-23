<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Opportunities\Infrastructure\Persistence\Eloquent\EloquentSavedOpportunity;

/**
 * @extends Factory<EloquentSavedOpportunity>
 */
final class SavedOpportunityFactory extends Factory
{
    protected $model = EloquentSavedOpportunity::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'student_id' => null,
            'opportunity_id' => null,
            'saved_at' => now(),
        ];
    }
}
