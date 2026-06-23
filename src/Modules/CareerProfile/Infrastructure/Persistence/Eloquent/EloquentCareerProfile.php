<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentCareerProfile extends Model
{
    use HasFactory;

    protected $table = 'career_profiles';

    protected $fillable = [
        'id',
        'student_id',
        'major',
        'summary',
        'interests',
        'languages',
    ];

    protected $casts = [
        'interests' => 'array',
        'languages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    public function portfolioItems(): HasMany
    {
        return $this->hasMany(EloquentPortfolioItem::class, 'career_profile_id', 'id');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(EloquentExperience::class, 'career_profile_id', 'id');
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(EloquentResume::class, 'career_profile_id', 'id');
    }

    public function careerGoals(): HasMany
    {
        return $this->hasMany(EloquentCareerGoal::class, 'career_profile_id', 'id');
    }
}
