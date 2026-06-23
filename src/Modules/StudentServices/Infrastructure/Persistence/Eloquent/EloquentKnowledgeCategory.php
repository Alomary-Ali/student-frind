<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentKnowledgeCategory extends Model
{
    protected $table = 'knowledge_categories';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'parent_id',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
