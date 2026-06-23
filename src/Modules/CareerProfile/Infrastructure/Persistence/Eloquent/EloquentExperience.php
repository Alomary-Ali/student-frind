<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentExperience extends Model
{
    use HasFactory;

    protected $table = 'experiences';

    protected $fillable = [
        'id',
        'career_profile_id',
        'company',
        'position',
        'description',
        'start_date',
        'end_date',
        'is_current',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_current' => 'boolean',
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
