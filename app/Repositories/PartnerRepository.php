<?php

namespace App\Repositories;

use App\Models\Partner;
use App\Interfaces\PartnerRepositoryInterface;
//use Auth;

class PartnerRepository implements PartnerRepositoryInterface
{
    public function getAll()
    {
        return Partner::all();
    }

    public function findById($id)
    {
        return Partner::findOrFail($id);
    }

    public function create(array $data)
        {
            
            // Initialize image path to null
            $imagePath = $data['image'] ?? null;   
            $partnerData = [     
                'title'     => ucwords($data['title']),                 
                'image'     => $imagePath, // Store the image path
            ];

            // Create the partner and return the created instance
            return Partner::create($partnerData);
        }


    public function update($id, array $data)
        {

            // Find the partner by ID
            $partner    = Partner::findOrFail($id);
            $imagePath  = $partner->image;
            if (isset($data['image']) && $data['image']->isValid()) {
                // If there's an existing image, delete it from public/uploads/partners
                if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $image      = $data['image'];
                $extension  = $image->getClientOriginalExtension();
                $filename   = time() . rand(10000, 99999) . '.' . $extension;
                $filePath   = 'uploads/partners/' . $filename;
                $image->move(public_path('uploads/partners'), $filename);
                $imagePath  = $filePath;
            }
          
            $updateData = [            
                'title'     => ucwords($data['title']),
                'image'     => $imagePath, // Assign the image path
            ];
            $partner->update($updateData);
            return $partner;
        }

    public function delete($id)
    {
        $partner = Partner::findOrFail($id);
        //dd($partner);
        //check if partner has an image
        if($partner->image && file_exists(public_path($partner->image))) {
            unlink(public_path($partner->image));
        }
        if($partner->delete()){
            return true;
        } else{
            return false;
        }
        
    }
}
