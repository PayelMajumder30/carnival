<?php

namespace App\Repositories;

use App\Models\{ItenaryList, ItineraryGallery};
use App\Interfaces\ItenarylistRepositoryInterface;
//use Auth;

class ItenarylistRepository implements ItenarylistRepositoryInterface
{
    public function getAll()
    {
        return ItenaryList::all();
    }

    public function findById($id)
    {
        return ItenaryList::findOrFail($id);
    }

    public function create(array $data)
    { 
        $supportData = [ 
            'main_image' =>$data['main_image'],    
            'title' => $data['title'],
            'short_description' => $data['short_description'], 
            'trip_durations' => $data['trip_durations'], 
            'selling_price' => $data['selling_price'],
            'actual_price' => $data['actual_price'], 
            'destination_id' => $data['destination_id'],
            'crm_itinerary_id' => $data['crm_itinerary_id'],
            'stay_by_division_journey' => $data['stay_by_division_journey'],
            'total_nights' => $data['total_nights'],
            'total_days' => $data['total_days'],
            'discount_type' => $data['discount_type'],
            'discount_value' => round($data['discount_value']),
            'discount_start_date' => $data['discount_start_date'],
            'discount_end_date' => $data['discount_end_date'],
        ];
    
       
        return ItenaryList::create($supportData);
    }

    public function update($id, array $data)
    {
        $itenary = ItenaryList::findOrFail($id);
        $itenary->update($data);
        return $itenary;
    }

    public function delete($id)
    {
        $itenary = ItenaryList::findOrFail($id);
        return $itenary->delete();
    }


    //itineraries/gallery
    public function gallery_create(array $data)
    {       
        $itineraryGalleryData = [  
            'itinerary_id'  => $data['itinerary_id'] ?? null, 
            'image' => $data['image'] ?? null,
        ];

        return ItineraryGallery::create($itineraryGalleryData);
    }

    public function gallery_update(array $data)
    {
        // Find the gallery ID
        $itineraryGallery = ItineraryGallery::findOrFail($data['id']);
        $imagePath  = $itineraryGallery->image;
        if (isset($data['image']) && $data['image']->isValid()) {
            if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            $image   = $data['image'];
            $extension  = $image->getClientOriginalExtension();
            $filename   = time() . rand(10000, 99999) . '.' . $extension;
            $filePath   = 'uploads/itinerary_galleries/' . $filename;
            $image->move(public_path('uploads/itinerary_galleries'), $filename);
            $imagePath  = $filePath;
        }
        
        $updateData = [            
            'image'   => $imagePath, // Assign the image path
        ];
        $itineraryGallery->update($updateData);
        return $itineraryGallery;
    }



}
