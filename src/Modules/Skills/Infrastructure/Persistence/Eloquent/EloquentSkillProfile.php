<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class EloquentSkillProfile extends Model
{
    use HasFactory;

    protected $table = 'skill_profiles';

    protected $fillable = [
        'id',
        'student_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    public function skills(): HasMany
    {
        return $this->hasMany(EloquentSkill::class, 'skill_profile_id', 'id');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(EloquentCertification::class, 'skill_profile_id', 'id');
    }
}
