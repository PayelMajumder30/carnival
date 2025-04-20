<?php

namespace App\Repositories;

use App\Models\{TripCategory, TripCategoryBanner};
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

    public function banner_create(array $data)
    {
        
        // Initialize image path to null
        //$imagePath      = $data['image'] ?? null;   
        $tripbannerCatdata = [  
            'trip_cat_id'   => $data['trip_cat_id'] ?? null,                   
            'image'         => $data['image'] ?? null, // Store the image path
        ];
        return TripCategoryBanner::create($tripbannerCatdata);
    }

    public function banner_update(array $data)
        {
            // Find the tripcategorybanner by ID
            $tripbanner = TripCategoryBanner::findOrFail($data['id']);
            $imagePath  = $tripbanner->image;
            if (isset($data['image']) && $data['image']->isValid()) {
                if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $image   = $data['image'];
                $extension  = $image->getClientOriginalExtension();
                $filename   = time() . rand(10000, 99999) . '.' . $extension;
                $filePath   = 'uploads/trip_category_banner/' . $filename;
                $image->move(public_path('uploads/trip_category_banner'), $filename);
                $imagePath  = $filePath;
            }
          
            $updateData = [            
                'image'     => $imagePath, // Assign the image path
            ];
            $tripbanner->update($updateData);
            return $tripbanner;
        }
}
