<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentKnowledgeArticle extends Model
{
    protected $table = 'knowledge_articles';

    protected $fillable = [
        'id',
        'category_id',
        'title',
        'slug',
        'content',
        'tags',
        'status',
        'view_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'view_count' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
