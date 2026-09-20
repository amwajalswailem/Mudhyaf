<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessCategory extends Model
{
    use HasFactory;

    protected $table = 'business_categories';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function businesses()
    {
        return $this->hasMany(Business::class, 'category_id');
    }
}
