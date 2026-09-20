<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attraction extends Model
{
    use HasFactory;

    protected $table = 'attractions';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'created_by',
        'image',
        'category_id',
        'created_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
    ];
    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'entity');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'entity');
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : asset('default-attraction.jpg');
    }

    public function isFavoritedBy($user)
    {
        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }
}
