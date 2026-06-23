<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentCareerGoal extends Model
{
    use HasFactory;

    protected $table = 'career_goals';

    protected $fillable = [
        'id',
        'career_profile_id',
        'title',
        'target_date',
        'status',
        'progress',
    ];

    protected $casts = [
        'target_date' => 'datetime',
        'progress' => 'integer',
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
