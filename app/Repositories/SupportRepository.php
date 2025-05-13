<?php

namespace App\Repositories;

use App\Models\Support;
use App\Interfaces\SupportRepositoryInterface;
//use Auth;

class SupportRepository implements SupportRepositoryInterface
{
    public function getAll()
    {
        return Support::all();
    }

    public function findById($id)
    {
        return Support::findOrFail($id);
    }

    // public function create(array $data)
    //     { 
    //         $Data = [     
    //             'title' => $data['title'],    
    //         ];
    //         // Create the banner media and return the created instance
    //         return Support::create($bannerData);
    //     }

    // public function update($id, array $data)
    //     {
    //         // Find the banner media by ID
    //         $banner     = Banner::findOrFail($id);      
    //         $updateData = [            
    //             'title'  => $data['title'],
    //         ];
    //         $banner->update($updateData);
    //         return $banner;
    //     }

    // public function delete($id)
    // {
    //     $banner = Banner::findOrFail($id);
    //     //dd($banner);
    //     if($banner->delete()){
    //         return true;
    //     } else{
    //         return false;
    //     }
        
    // }
}
