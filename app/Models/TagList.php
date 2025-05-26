<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagList extends Model
{
    use HasFactory;
    protected $table = 'tag_list';

    protected $fillable = [
        'title',
        'status'
    ];

    public function itenaries()
    {
        return $this->belongsToMany(ItenaryList::class, 'itenaries_tags', 'tag_id', 'itenary_id');
    }

    public function packages()
    {
        return $this->hasMany(DestinationWisePopularPackageTag::class, 'tag_id');
    }

}
