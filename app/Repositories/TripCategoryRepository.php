<?php

namespace App\Repositories;

use App\Models\TripCategory;
use App\Interfaces\TripCategoryRepositoryInterface;
//use Auth;

class TripCategoryRepository implements TripCategoryRepositoryInterface
{
    public function getAll()
    {
        return TripCategory::all();
    }

    public function findById($id)
    {
        return TripCategory::findOrFail($id);
    }

    public function create(array $data)
        { 
            $tripcatData = [     
                'title' => $data['title'],    
            ];
            // Create the category media and return the created instance
            return TripCategory::create($tripcatData);
        }

    public function update($id, array $data)
        {
            // Find the category media by ID
            $tripcat     = TripCategory::findOrFail($id);      
            $updateData  = [            
                'title'  => $data['title'],
            ];
            $tripcat->update($updateData);
            return $tripcat;
        }

    public function delete($id)
    {
        $tripcat = TripCategory::findOrFail($id);
        //dd($tripcat);
        if($tripcat->delete()){
            return true;
        } else{
            return false;
        }       
    }
}
