<?php

namespace App\Repositories;

use App\Models\DestinationWisePopularPackages;
use App\Interfaces\PopularPackagesRepositoryInterface;
use Auth;
use Illuminate\Support\Facades\Storage;


class PopularPackagesRepository implements PopularPackagesRepositoryInterface
{
    public function getAll()
    {
        return DestinationWisePopularPackages::all();
    }

    public function findById($id)
    {
        return DestinationWisePopularPackages::findOrFail($id);
    }
}