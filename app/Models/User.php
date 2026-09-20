<?php

namespace App\Models;

use App\Constants\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'password_hash',
        'role',
        'created_at'
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function businesses()
    {
        return $this->hasMany(Business::class, 'owner_id');
    }

    public function createdAttractions()
    {
        return $this->hasMany(Attraction::class, 'created_by');
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function approvedEvents()
    {
        return $this->hasMany(Event::class, 'approved_by');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function userPreference()
    {
        return $this->hasOne(UserPreference::class, 'user_id');
    }

    public function recommendationLogs()
    {
        return $this->hasMany(RecommendationLog::class);
    }


    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === Role::USER;
    }

    public function isBusinessOwner(): bool
    {
        return $this->role === Role::BUSINESS_OWNER;
    }
}
