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
        'short_description',
        'duration',
        'selling_price',
        'actual_price',
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
        return $this->belongsToMany(TagList::class, 'itenaries_tag', 'itenary_id', 'tag_id');
    }


    /*
    * Relationship with itinerary_galleries
    */
    public function itineraryGallery(){
        return $this->hasMany(itinerary_gallery::class, 'itinerary_id', 'id');
    }


}
