<?php

namespace App\Repositories;

use App\Models\SocialMedia;
use App\Interfaces\SocialRepositoryInterface;
//use Auth;

class SocialRepository implements SocialRepositoryInterface
{
    public function getAll()
    {
        return SocialMedia::all();
    }

    public function findById($id)
    {
        return SocialMedia::findOrFail($id);
    }

    public function create(array $data)
        {
            $link       = isset($data['social_link']) ? $data['social_link'] : null;
            // Initialize image path to null
            $imagePath  = $data['image'] ?? null;   
            $socialData = [     
                'title' => $data['title'],    
                'link'  => $link,             
                'image' => $imagePath, // Store the image path
            ];
            // Create the social media and return the created instance
            return SocialMedia::create($socialData);
        }

    public function update($id, array $data)
        {
            // Find the social media by ID
            $social     = SocialMedia::findOrFail($id);
            $imagePath  = $social->image;
            $link       = isset($data['social_link']) ? $data['social_link'] : $social->link;
            if (isset($data['image']) && $data['image']->isValid()) {
                // If there's an existing image, delete it from public/uploads/social
                if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $image      = $data['image'];
                $extension  = $image->getClientOriginalExtension();
                $filename   = time() . rand(10000, 99999) . '.' . $extension;
                $filePath   = 'uploads/social/' . $filename;
                $image->move(public_path('uploads/social'), $filename);
                $imagePath  = $filePath;
            }
          
            $updateData = [            
                'title'  => $data['title'],
                'link'   => $link,
                'image'  => $imagePath, // Assign the image path
            ];
            $social->update($updateData);
            return $social;
        }

    public function delete($id)
    {
        $social = SocialMedia::findOrFail($id);
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
