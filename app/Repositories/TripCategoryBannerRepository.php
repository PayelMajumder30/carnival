<?php

namespace App\Repositories;

use App\Models\{TripCategory, TripCategoryBanner};
use App\Interfaces\TripCategoryBannerRepositoryInterface;
//use Auth;

class TripCategoryBannerRepository implements TripCategoryBannerRepositoryInterface
{
    public function getAll()
    {
        return TripCategoryBanner::all();
    }

    public function findById($id)
    {
        return TripCategoryBanner::findOrFail($id);
    }

    public function create(array $data)
        {
            
            // Initialize image path to null
            $imagePath      = $data['image'] ?? null;   
            $tripbannerdata = [                     
                'image'   => $imagePath, // Store the image path
            ];
            return TripCategoryBanner::create($tripbannerdata);
        }


    public function update($id, array $data)
        {

            // Find the tripcategorybanner by ID
            $tripbanner     = TripCategoryBanner::findOrFail($id);
            $imagePath      = $tripbanner->image;
            if (isset($data['image']) && $data['image']->isValid()) {
                if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $image      = $data['image'];
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

    public function delete($id)
    {
        $tripbanner = TripCategoryBanner::findOrFail($id);
        //dd($tripbanner);
        //check if tripbanner has an image
        if($tripbanner->image && file_exists(public_path($tripbanner->image))) {
            unlink(public_path($tripbanner->image));
        }
        if($tripbanner->delete()){
            return true;
        } else{
            return false;
        }
        
    }
}
