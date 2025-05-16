<?php

namespace App\Repositories;

use App\Models\{DestinationWisePackageCat, PackageCategory};
use App\Interfaces\DestiantionPackageInterface;
//use Auth;

class DestiantionPackageRepository implements DestiantionPackageInterface
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
           $packageCatData  = [     
                'title' => $data['title'], 
                //'destination_id' => $data['destination_id'],   
            ];
            // Create the banner media and return the created instance
            return PackageCategory::create($packageCatData );
        }

    public function update($id, array $data)
        {
            $destiPckgCat = PackageCategory::findOrFail($id);
            $updatePckgData = [
                'title' => $data['title'],
            ];
            $destiPckgCat->update($updatePckgData);
            return $destiPckgCat;
        }

    public function delete($id)
    {
        $social = PackageCategory::findOrFail($id);
        //dd($social);
        //check if social media has an image
        if($social->image && file_exists(public_path($social->image))) {
            unlink(public_path($social->image));
        }
        if($social->delete()){
            return true;
        } else{
            return false;
        }
        
    }
}
