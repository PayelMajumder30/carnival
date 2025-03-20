<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "articles";
    protected $fillable = [
        'admin_id',
        'title',
        'slug',
        'sub_title',
        'content',
        'meta_type',
        'meta_description',
        'meta_keywords',
        'status',
        'image',
    ];
}

