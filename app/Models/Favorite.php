<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
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
