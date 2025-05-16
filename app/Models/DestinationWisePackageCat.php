<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationWisePackageCat extends Model
{
    use HasFactory;
    protected $table = 'destination_wise_package_category';
    protected $fillable = [
        'destination_id',
        'title',
        'status',
    ];

    public function packgDestination(){
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}
