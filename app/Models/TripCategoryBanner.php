<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategoryBanner extends Model
{
    use HasFactory;

    protected $table = "trip_category_banner";
    protected $fillable = [
        'trip_cat_id','image', 'status'
    ];

    public function tripcategory(){
        return $this->belongsTo(TripCategory::class, 'trip_cat_id', 'id');
    }
}
