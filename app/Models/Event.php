<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'start_time',
        'end_time',
        'created_by',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'status',
        'category_id',
        'image',
        'created_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'entity');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'entity');
    }
    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
    }
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset($this->image)
            : asset('event.jpg');
    }

    public function isFavoritedBy($user)
    {
        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }
}
