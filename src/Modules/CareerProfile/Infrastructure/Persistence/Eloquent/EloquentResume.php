<?php

declare(strict_types=1);

namespace Modules\CareerProfile\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentResume extends Model
{
    use HasFactory;

    protected $table = 'resumes';

    protected $fillable = [
        'id',
        'career_profile_id',
        'template',
        'content',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
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
