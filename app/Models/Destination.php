<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $table = 'destinations';
    protected $fillable = [
        'country_id',
        'crm_destination_id',
        'destination_name',
        'slug',
        'image',
        'logo',
        'status',
        'banner_image',
        'short_desc'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function tripcategorydestination() {
        return $this->hasMany(TripCategoryDestination::class, 'destination_id');
    }

    public function packcategorydestination() {
        return $this->hasMany(DestinationWisePackageCat::class, 'destination_id');
    }

    public function activities()
    {
        return $this->hasMany(TripCategoryActivity::class, 'destination_id');
    }

    /*
    * Relationship with `destination_wise_itinerary` table
    */
    // public function destinationItineraries(){
    //     return $this->hasMany(DestinationWiseItinerary::class, 'destination_id', 'destination_id');
    // }
    public function destinationItineraries()
    {
        return $this->hasMany(DestinationWiseItinerary::class, 'destination_id', 'id')
                    ->with(['packageCategory', 'itinerary']);
    }

    
    /*
    * Relationship with `itenary_list` table
    */
    public function itineraries()
    {
        return $this->hasMany(ItenaryList::class, 'destination_id');
    }


    /*
    * Relationship with `about_destinations` table
    */
    public function aboutDestination()
    {
        return $this->hasMany(AboutDestination::class, 'destination_id');
    }


    /*
    * Relationship with `destination_wise_popular_package_tags` table
    */
    public function popularItineraries()
    {
        return $this->hasMany(DestinationWisePopularPackages::class, 'destination_id', 'id');
    }

    
    /*
    * Relationship with `packages_from_top_cities` table
    */
    public function packagesFromTopCities()
    {
        return $this->hasMany(PackagesFromTopCities::class, 'destination_id');
    }

}
