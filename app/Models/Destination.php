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
        'image',
        'status',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function tripcategorydestination() {
        return $this->hasMany(TripCategoryDestination::class, 'destination_id');
    }
}
