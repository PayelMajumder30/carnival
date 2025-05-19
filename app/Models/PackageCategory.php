<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'package_categories';
    protected $fillable = [
        'title',
        'status',
    ];

    public function pckgCategoryItineraries(){
        return $this->hasMany(DestinationWiseItinerary::class, 'package_id');
    }
}
