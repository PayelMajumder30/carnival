<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategory extends Model
{
    use HasFactory;

    protected $table = "trip_categories";
    protected $fillable = [
        'title', 'is_header', 'short_desc', 'is_highlighted', 'status'
    ];

    public function tripcategorybanner() {
        return $this->hasMany(TripCategoryBanner::class, 'trip_cat_id');
    }

    public function tripcategorydestination() {
        return $this->hasMany(TripCategoryDestination::class, 'trip_cat_id');
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class, 'trip_cat_id');
    }

}
