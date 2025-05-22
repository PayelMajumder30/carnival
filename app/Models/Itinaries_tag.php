<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinaries_tag extends Model
{
    use HasFactory;
    protected $table = 'itenaries_tags';
    protected $fillable = [
        'tag_id',
        'itenary_id',

    ];
}
