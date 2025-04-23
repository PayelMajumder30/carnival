<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = [
        'crm_country_id',
        'country_name',
        'status',
    ];

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

}
