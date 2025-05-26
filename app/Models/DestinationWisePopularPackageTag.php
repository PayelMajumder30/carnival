<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationWisePopularPackageTag extends Model
{
    use HasFactory;

    protected $table = 'destination_wise_popular_package_tags';

    protected $fillable = [
        'popular_package_id',
        'tag_id'
    ];

    public function package()
    {
        return $this->belongsTo(DestinationWisePopularPackages::class, 'popular_package_id');
    }

    public function tag()
    {
        return $this->belongsTo(TagList::class, 'tag_id');
    }
}
