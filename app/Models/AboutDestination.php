<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutDestination extends Model
{
    use HasFactory;
    protected $table = 'about_destinations';
    protected $fillable = ['destination_id', 'content'];

    /*
    * Relationship with destination
    */
    public function destination(){
        return $this->belongsTo(Destination::class, 'destination_id', 'id');
    }
}
