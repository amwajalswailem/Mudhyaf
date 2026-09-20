<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecommendationLog extends Model
{
    use HasFactory;

    protected $table = 'recommendation_logs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'score',
        'reason',
        'generated_at',
    ];

    protected $casts = [
        'score' => 'decimal:4',
        'reason' => 'array',
        'generated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
