<?php

namespace App\Repositories;

use App\Models\AboutDestination;
use App\Interfaces\AboutDestinationInterface;
//use Illuminate\Support\Facades\Storage;


class AboutDestinationRepository implements AboutDestinationInterface
{
    public function getAll()
    {
        return AboutDestination::all();
    }

     public function findById($id)
    {
        return AboutDestination::findOrFail($id);
    }

    public function create(array $data)
    {
        $aboutDestination = AboutDestination::where('destination_id', $data['destination_id'])->first();

        if ($aboutDestination) {
            // Update existing record
            $aboutDestination->update(['content' => $data['content']]);
            return $aboutDestination;
        }

        // Otherwise, create a new one
        return AboutDestination::updateOrCreate([
            'destination_id' => $data['destination_id'],
            'content' => $data['content'],
        ]);
    }

    public function update($id, array $data)
    {
        // Find the content by ID
        $aboutDestination = AboutDestination::findOrFail($id);
    
        $updateData = [
            'content' => $data['content'],
        ];   
        // Update the content and return it
        $aboutDestination->update($updateData);
    
        return $aboutDestination;
    }

    public function delete($id)
    {
        $content = AboutDestinationcontent::findOrFail($id);
        //dd($banner);
        if($content->delete()){
            return true;
        } else{
            return false;
        }
        
    }

}