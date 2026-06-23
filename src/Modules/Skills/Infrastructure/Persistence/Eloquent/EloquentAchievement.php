<?php

declare(strict_types=1);

namespace Modules\Skills\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EloquentAchievement extends Model
{
    use HasFactory;

    protected $table = 'achievements';

    protected $fillable = [
        'id',
        'student_id',
        'type',
        'title',
        'description',
        'badge_url',
        'unlocked_at',
        'created_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    public $timestamps = false; // Custom locked_at and created_at timestamps are used
}
