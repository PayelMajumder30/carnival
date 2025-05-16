<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCategoryActivities extends Model
{
    use HasFactory;
    protected $table = "trip_category_activities";
    protected $fillable = ['destination_id','trip_cat_id', 'activity_name', 'image', 'logo', 'status'];

    public function tripcategory(){
        return $this->belongsTo(TripCategory::class, 'trip_cat_id', 'id');
    }

    public function tripdestination(){
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}
