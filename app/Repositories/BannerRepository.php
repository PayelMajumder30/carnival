<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Interfaces\BannerRepositoryInterface;
//use Auth;

class BannerRepository implements BannerRepositoryInterface
{
    public function getAll()
    {
        return Banner::all();
    }

    public function findById($id)
    {
        return Banner::findOrFail($id);
    }

    public function create(array $data)
        { 
            $bannerData = [     
                'title' => $data['title'],    
            ];
            // Create the banner media and return the created instance
            return Banner::create($bannerData);
        }

    public function update($id, array $data)
        {
            // Find the banner media by ID
            $banner     = Banner::findOrFail($id);      
            $updateData = [            
                'title'  => $data['title'],
            ];
            $banner->update($updateData);
            return $banner;
        }

    public function delete($id)
    {
        $banner = Banner::findOrFail($id);
        //dd($banner);
        if($banner->delete()){
            return true;
        } else{
            return false;
        }
        
    }
}
