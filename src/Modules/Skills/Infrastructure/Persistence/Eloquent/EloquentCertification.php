<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentCertification extends Model
{
    use HasFactory;

    protected $table = 'certifications';

    protected $fillable = [
        'id',
        'skill_profile_id',
        'name',
        'issuer',
        'issue_date',
        'expiry_date',
        'credential_url',
        'verification_code',
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'expiry_date' => 'datetime',
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
