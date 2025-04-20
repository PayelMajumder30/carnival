<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhyChooseUs extends Model
{
    use HasFactory;
    protected $table = "why_chhose_us";
    protected $fillable = [
        'title', 'desc', 'status', 
    ];
}
