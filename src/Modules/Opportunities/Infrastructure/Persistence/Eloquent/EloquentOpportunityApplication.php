<?php

declare(strict_types=1);

namespace Modules\Opportunities\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentOpportunityApplication extends Model
{
    use HasFactory;

    protected $table = 'opportunity_applications';

    protected $fillable = [
        'id',
        'opportunity_id',
        'student_id',
        'application_status',
        'applied_at',
        'notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(EloquentOpportunity::class, 'opportunity_id', 'id');
    }
}
