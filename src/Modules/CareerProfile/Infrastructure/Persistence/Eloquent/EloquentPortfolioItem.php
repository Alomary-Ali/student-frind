<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentPortfolioItem extends Model
{
    use HasFactory;

    protected $table = 'portfolio_items';

    protected $fillable = [
        'id',
        'career_profile_id',
        'title',
        'description',
        'project_url',
        'github_url',
        'start_date',
        'end_date',
        'technologies',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'technologies' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    public function careerProfile(): BelongsTo
    {
        return $this->belongsTo(EloquentCareerProfile::class, 'career_profile_id', 'id');
    }
}
