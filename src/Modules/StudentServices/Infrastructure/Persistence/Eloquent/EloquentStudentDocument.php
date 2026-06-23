<?php

declare(strict_types=1);

namespace Modules\StudentServices\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

final class EloquentStudentDocument extends Model
{
    protected $table = 'student_documents';

    protected $fillable = [
        'id',
        'student_id',
        'type',
        'title',
        'file_path',
        'status',
        'verification_code',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;
}
