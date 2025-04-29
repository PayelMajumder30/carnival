<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategoryDestination extends Model
{
    use HasFactory;

    protected $table = "trip_category_destinations";
    protected $fillable = ['destination_id', 'status','trip_cat_id', 'start_price'];

    public function tripcategory(){
        return $this->belongsTo(TripCategory::class, 'trip_cat_id', 'id');
    }

    public function tripdestination(){
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}
