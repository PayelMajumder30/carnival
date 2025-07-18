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
        'positions',
        'status',
    ];

    public function pckgCategoryItineraries(){
        return $this->hasMany(DestinationWiseItinerary::class, 'package_id');
    }

    /*
    * Relationship with `itenary_list` table
    */

    public function itineraries()
    {
        return $this->hasMany(ItenaryList::class, 'package_id');
    }

    /*
    * Relationship with `lead_generate` table
    */
    public function leadGenerate()
    {
        return $this->hasOne(LeadGenerate::class, 'package_id');
    }
}
