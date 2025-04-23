<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategoryDestination extends Model
{
    use HasFactory;

    protected $table = "trip_category_destinations";
    protected $fillable = ['destination_id', 'status','trip_cat_id'];

    public function tripcategory(){
        return $this->belongsTo(TripCategory::class, 'trip_cat_id', 'id');
    }
}
