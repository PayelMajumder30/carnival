<?php

namespace App\Repositories;

use App\Models\ItenaryList;
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
            'duration' => $data['duration'],
            'selling_price' => $data['selling_price'],
            'actual_price' => $data['actual_price'], 
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
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


}
