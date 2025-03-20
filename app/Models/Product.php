<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public function categoryDetails() {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }
    function ServiceDetails()
    {
        return $this->hasMany('App\Models\ProductService', 'product_id', 'id');
    }

}
