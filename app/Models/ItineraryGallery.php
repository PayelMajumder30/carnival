<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryGallery extends Model
{
    use HasFactory;

    protected $table = 'itinerary_galleries';
    protected $fillable = [
        'title',
        'itinerary_id',
        'image'
    ];

    /*
    * Relationship with `itenary_list` table
    */
    public function itinerary()
    {
        return $this->belongsTo(ItenaryList::class, 'itinerary_id');
    }
}
