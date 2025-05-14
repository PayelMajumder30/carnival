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

    public function create(array $data)
    { 
        $supportData = [     
            'title' => $data['title'],
            'description' => $data['description'],    
        ];
        // Create the banner media and return the created instance
        return Support::create($supportData);
    }


    public function update($id, array $data)
    {
        // Find the banner media by ID
        $support     = Support::findOrFail($id);      
        $supportData = [            
            'title' => $data['title'],
            'description'  => $data['description'], 
        ];
        $support->update($supportData);
        return $support;
    }
    
    public function delete($id)
    {
        $support = Support::findOrFail($id);
        //dd($banner);
        if($support->delete()){
            return true;
        } else{
            return false;
        }
        
    }


}
