<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentDocumentRequest extends Model
{
    protected $table = 'document_requests';

    protected $fillable = [
        'id',
        'student_id',
        'document_type',
        'status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
