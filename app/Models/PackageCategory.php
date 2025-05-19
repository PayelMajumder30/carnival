<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
    use HasFactory;
    protected $table = 'package_category';
    protected $fillable = [
        'title',
        'status',
    ];

    public function pckgCategoryItineraries(){
        return $this->hasMany(DestinationWiseItinerary::class, 'package_id');
    }
}
