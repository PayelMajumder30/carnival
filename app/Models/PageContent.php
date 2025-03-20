<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "page_contents";
    protected $fillable = ['page', 'title', 'short_description', 'description', 'image','meta_title', 'meta_description', 'meta_keyword'];
}


