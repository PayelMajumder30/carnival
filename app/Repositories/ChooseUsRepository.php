<?php

namespace App\Repositories;

use App\Models\WhyChooseUs;
use App\Interfaces\ChooseUsRepositoryInterface;
//use Auth;

class ChooseUsRepository implements ChooseUsRepositoryInterface
{
    public function getAll()
    {
        return WhyChooseUs::all();
    }

    public function findById($id)
    {
        return WhyChooseUs::findOrFail($id);
    }

    public function create(array $data)
        { 
            $chooseusData = [     
                'title' => $data['title'], 
                'desc'  => $data['desc']   
            ];
            // Create the banner media and return the created instance
            return WhyChooseUs::create($chooseusData);
        }

    // public function update($id, array $data)
    //     {
    //         // Find the banner media by ID
    //         $banner     = WhyChooseUs::findOrFail($id);      
    //         $updateData = [            
    //             'title'  => $data['title'],
    //         ];
    //         $banner->update($updateData);
    //         return $banner;
    //     }

    // public function delete($id)
    // {
    //     $banner = WhyChooseUs::findOrFail($id);
    //     //dd($banner);
    //     if($banner->delete()){
    //         return true;
    //     } else{
    //         return false;
    //     }
        
    // }
}
