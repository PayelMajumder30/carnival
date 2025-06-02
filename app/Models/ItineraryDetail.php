<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryDetail extends Model
{
  use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'header',
        'field',
        'value',
        'images',
    ];
    
    
    protected $casts = [
        'images' => 'array',
    ];
    // Optional relation
    public function itinerary()
    {
        return $this->belongsTo(ItenaryList::class);
    }
}
