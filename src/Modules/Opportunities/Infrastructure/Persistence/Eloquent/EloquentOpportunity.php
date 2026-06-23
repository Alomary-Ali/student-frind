<?php

declare(strict_types=1);

namespace Modules\Opportunities\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EloquentOpportunity extends Model
{
    use HasFactory;

    protected $table = 'opportunities';

    protected $fillable = [
        'id',
        'title',
        'description',
        'provider',
        'type',
        'location',
        'country',
        'deadline',
        'apply_url',
        'status',
        'metadata',
        'source_url',
        'image_url',
        'tags',
    ];

    protected $casts = [
        'metadata' => 'array',
        'tags' => 'array',
        'deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';
}
