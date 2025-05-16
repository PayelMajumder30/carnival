<?php

namespace App\Repositories;

use App\Models\DestinationWisePackageCat;
use App\Interfaces\DestiantionPackageInterface;
//use Auth;

class DestiantionPackageRepository implements DestiantionPackageInterface
{
    public function getAll()
    {
        return DestinationWisePackageCat::all();
    }

    public function findById($id)
    {
        return DestinationWisePackageCat::findOrFail($id);
    }

    public function create(array $data)
        {
           $destiPckgCat = [     
                'title' => $data['title'], 
                'destination_id' => $data['destination_id'],   
            ];
            // Create the banner media and return the created instance
            return DestinationWisePackageCat::create($destiPckgCat);
        }

    public function update($id, array $data)
        {
            $destiPckgCat = DestinationWisePackageCat::findOrFail($id);
            $updatePckgData = [
                'title' => $data['title'],
            ];
            $destiPckgCat->update($updatePckgData);
            return $destiPckgCat;
        }

    public function delete($id)
    {
        $social = DestinationWisePackageCat::findOrFail($id);
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
