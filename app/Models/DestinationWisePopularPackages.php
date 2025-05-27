<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationWisePopularPackages extends Model
{
    use HasFactory;
    protected $table = 'destination_wise_popular_packages';

    protected $fillable = [
        'destination_id',
        'itinerary_id',
        'status'
    ];

    public function popularitinerary()
    {
        return $this->belongsTo(ItenaryList::class, 'itinerary_id', 'id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }

    // public function tags()
    // {
    //     return $this->hasMany(DestinationWisePopularPackageTag::class, 'popular_package_id');
    // }

    public function tags()
    {
        return $this->belongsToMany(TagList::class, 'destination_wise_popular_package_tags', 'popular_package_id', 'tag_id');
    }

}
