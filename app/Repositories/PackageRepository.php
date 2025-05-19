<?php

namespace App\Repositories;

use App\Models\{PackageCategory};
use App\Interfaces\PackageInterface;
//use Auth;

class PackageRepository implements PackageInterface
{
    public function getAll()
    {
        return PackageCategory::all();
    }

    public function findById($id)
    {
        return PackageCategory::findOrFail($id);
    }

    public function create(array $data)
    {
        $packageData = [     
            'title'     => ucwords($data['title']),                 
        ];
        return PackageCategory::create($packageData);
    }

    public function update($id, array $data)
    {
        $package   = PackageCategory::findOrFail($id);      
        $package->update($data);
        return $package;
    }

    public function delete($id)
    {
        $data = PackageCategory::findOrFail($id);
        if($data->delete()){
            return true;
        } else{
            return false;
        }
        
    }
}
