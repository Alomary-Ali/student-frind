<?php

declare(strict_types=1);

namespace Modules\Opportunities\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class EloquentRecommendation extends Model
{
    use HasFactory;

    protected $table = 'recommendations';

    protected $fillable = [
        'id',
        'student_id',
        'opportunity_id',
        'score',
        'reason',
        'generated_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'generated_at' => 'datetime',
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
