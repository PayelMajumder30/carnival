<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Interfaces\BlogRepositoryInterface;
use Auth;
use Illuminate\Support\Facades\Storage;


class BlogRepository implements BlogRepositoryInterface
{
    public function getAll()
    {
        return Blog::all();
    }

    public function findById($id)
    {
        return Blog::findOrFail($id);
    }

    public function create(array $data)
    {
        // Generate the slug based on the title
        $slug = slugGenerate($data['title'], 'blogs');
        // Prepare the blog data
        $blogData = [
            'admin_id'          => auth()->id(),  // Assuming the admin is logged in
            'title'             => $data['title'],
            'slug'              => $slug,
            'short_desc'        => $data['short_desc'] ?? '',
            'desc'              => $data['desc'] ?? '',
            'meta_type'         => $data['meta_type'] ?? null,
            'meta_description'  => $data['meta_description'] ?? null,
            'meta_keywords'     => $data['meta_keywords'] ?? null,
            'status'            => $data['status'] ?? 1, // Default to 1 (active)
            'image'             => $data['image'] ?? null, // Store the image path
        ];

        // Create the blog and return the created instance
        return Blog::create($blogData);
    }


    public function update($id, array $data)
    {
        // Find the blog by ID
        $blog = Blog::findOrFail($id);
    
        // Retrieve the existing image path (if any)
        $imagePath = $blog->image;

        if (isset($data['image']) && $data['image']->isValid()) {
            // If there's an existing image, delete it from public/uploads/social
            if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                unlink(public_path($imagePath));
            }
            $image      = $data['image'];
            $extension  = $image->getClientOriginalExtension();
            $filename   = time() . rand(10000, 99999) . '.' . $extension;
            $filePath   = 'uploads/blogs/' . $filename;
            $image->move(public_path('uploads/blogs'), $filename);
            $imagePath  = $filePath;
        }
    
        // Generate slug if it's not already provided
        if (!isset($data['slug'])) {
        $data['slug'] = slugGenerateUpdate($data['title'], 'blogs', $id);
        }
    
        // Prepare the data to update the blog
        $updateData = [
            'admin_id' => auth()->id(),
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_desc' => $data['short_desc'] ?? '',
            'desc' => $data['desc'] ?? '',
            'meta_type' => $data['meta_type'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'status' => $data['status'] ?? 1,
            'image' => $imagePath, // Add the image path
        ];
    
        // Update the blog and return it
        $blog->update($updateData);
    
        return $blog;
    }

    public function delete($id)
    {
        //dd($id);
        $blog = Blog::findOrFail($id);
        if($blog->image && file_exists(public_path($blog->image))) {
            unlink(public_path($blog->image));
        }
        if($blog->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
