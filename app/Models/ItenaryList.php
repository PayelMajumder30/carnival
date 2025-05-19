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
        'selling_price',
        'actual_price',
        'status'
    ];

    public function itineraryItineraries(){
        return $this->hasMany(DestinationWiseItinerary::class, 'itinerary_id');
    }
}
