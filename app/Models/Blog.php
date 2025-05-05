<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = "blogs";
    
    protected $fillable = [
        'admin_id',
        'title',
        'slug',
        'short_desc',
        'desc',
        'meta_type',
        'meta_description',
        'meta_keywords',
        'status',
        'image',
    ];
}
