<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadGenerate extends Model
{
    use HasFactory;
    protected $table = 'lead_generate';
    protected $fillable = [
        'destination_id',
        'itinerary_id',
        'package_id',
        'customerName',
        'travelLocation',
        'travelDuration',
        'email',
        'whatsapp',
        'travellers',
        'startDate',
        'endDate'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function itinerary()
    {
        return $this->belongsTo(ItinaryList::class, 'itinerary_id');
    }

    public function package()
    {
        return $this->belongsTo(PackageCategory::class, 'package_id');
    }

}
