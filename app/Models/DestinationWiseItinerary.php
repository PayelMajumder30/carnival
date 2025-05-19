<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationWiseItinerary extends Model
{
    use HasFactory;
    protected $table = 'destination_wise_itinerary';
    protected $fillable = [
        'destination_id',
        'package_id',
        'itinerary_id',
        'status',
    ];

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

    /*
    * Relationship with itinerary
    */
    public function itinerary(){
        return $this->belongsTo(ItenaryList::class, 'itinerary_id', 'id');
    }
}
