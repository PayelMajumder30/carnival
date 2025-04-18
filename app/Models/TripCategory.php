<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategory extends Model
{
    use HasFactory;

    protected $table = "trip_categories";
    protected $fillable = [
        'title', 'status'
    ];

    public function tripcategorybanner() {
        return $this->hasMany(TripCategoryBanner::class, 'trip_cat_id');
    }

}
