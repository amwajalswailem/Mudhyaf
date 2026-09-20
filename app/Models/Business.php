<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory;

    protected $table = 'businesses';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'owner_id',
        'category_id',
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'phone',
        'working_hours',
        'price_level',
        'image',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'status',
        'created_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
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

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return asset('business.jpg');
        }

        if (Str::startsWith($this->image, ['http://', 'https://', '//'])) {
            return $this->image;
        }

        return asset(ltrim($this->image, '/'));
    }

    public function isFavoritedBy($user)
    {
        return $this->favorites()
            ->where('user_id', $user->id)
            ->exists();
    }
}
