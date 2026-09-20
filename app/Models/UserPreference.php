<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPreference extends Model
{
    use HasFactory;

    protected $table = 'user_preferences';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'preferred_price_level',
        'min_budget',
        'max_budget',
        'preferred_categories',
        'preferred_tags',
        'preferred_visit_time',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'min_budget' => 'decimal:2',
        'max_budget' => 'decimal:2',
        'preferred_categories' => 'array',
        'preferred_tags' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
