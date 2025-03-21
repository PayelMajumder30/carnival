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

    public function update($id, array $data)
        {
            // Find the banner media by ID
            $choose     = WhyChooseUs::findOrFail($id);      
            $chooseusData = [            
                'title' => $data['title'],
                'desc'  => $data['desc'], 
            ];
            $choose->update($chooseusData);
            return $choose;
        }

    public function delete($id){
        $choose = WhyChooseUs::findOrFail($id);
        if($choose->delete()){
            return true;
        } else{
            return false;
        }
    }
}
