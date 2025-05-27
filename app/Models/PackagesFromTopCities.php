<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagesFromTopCities extends Model
{
    use HasFactory;

    protected $table = 'packages_from_top_cities';
    protected $fillable = [
        'destination_id',
        'city',
        'title',
        'city',
        'slug',
        'status'
    ];

    /*
    * Relationship with destination
    */
    public function destination(){
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}
