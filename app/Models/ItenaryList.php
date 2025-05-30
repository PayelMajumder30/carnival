<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItenaryList extends Model
{
    use HasFactory;
    protected $table = 'itenary_list';
    protected $fillable = [

        'main_image',
        'title',
        'slug',
        'short_description',
        
        'selling_price',
        'actual_price',
        'destination_id',
        'crm_itinerary_id',
        'stay_by_division_journey',
        'total_nights',
        'total_days',
        'trip_durations',
        'discount_type',
        'discount_value',
        'discount_start_date',
        'discount_end_date',
        'status'
    ];

    public function itineraryItineraries(){
        return $this->hasMany(DestinationWiseItinerary::class, 'itinerary_id');
    }

    /*
    * Relationship with destination
    */
    public function destination(){
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }

    /*
    * Relationship with package category
    */
    public function packageCategory(){
        return $this->belongsTo(PackageCategory::class, 'package_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(TagList::class, 'itineraries_tags', 'itenary_id', 'tag_id');
    }


    /*
    * Relationship with itinerary_galleries
    */
    public function itineraryGallery(){
        return $this->hasMany(ItineraryGallery::class, 'itinerary_id', 'id');
    }


}
