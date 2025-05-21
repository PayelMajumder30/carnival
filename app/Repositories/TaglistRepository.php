<?php

namespace App\Repositories;

use App\Models\TagList;
use App\Interfaces\TaglistRepositoryInterface;


class TaglistRepository implements TaglistRepositoryInterface
{
    public function getAll()
    {
        return TagList::all();
    }

    public function findById($id)
    {
        return TagList::findOrFail($id);
    }

    public function create(array $data)
    {
        $tagData = [     
            'title'     => ucwords($data['title']),                 
        ];
        return TagList::create($tagData);
    }

    public function update($id, array $data)
    {
        $tag   = TagList::findOrFail($id);      
        $tag->update($data);
        return $tag;
    }

    public function delete($id)
    {
        $data = TagList::findOrFail($id);
        if($data->delete()){
            return true;
        } else{
            return false;
        }
        
    }
}
