<?php

declare(strict_types=1);

namespace Modules\Career\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentPublicPortfolio extends Model
{
    protected $table = 'public_portfolios';

    protected $fillable = [
        'id',
        'student_id',
        'slug',
        'title',
        'bio',
        'theme',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
