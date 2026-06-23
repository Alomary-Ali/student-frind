<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentSkill extends Model
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = [
        'id',
        'skill_profile_id',
        'name',
        'category',
        'level',
        'years_of_experience',
        'last_used',
    ];

    protected $casts = [
        'years_of_experience' => 'integer',
        'last_used' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    public function skillProfile(): BelongsTo
    {
        return $this->belongsTo(EloquentSkillProfile::class, 'skill_profile_id', 'id');
    }
}
