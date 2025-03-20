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

    // Initialize image path to null
    $imagePath = null;

    // Check if the image exists in the data array
    if (isset($data['image'])) {
        $image = $data['image']; // Get the image from the data array
        $extension = $image->getClientOriginalExtension(); // Get the file extension
        $filename = time() . '-' . uniqid() . '.' . $extension; // Generate a unique filename
        $imagePath = $image->storeAs('blogs', $filename, 'public'); // Store the image
    }

    // Prepare the blog data
    $blogData = [
        'admin_id' => auth()->id(),  // Assuming the admin is logged in
        'title' => $data['title'],
        'slug' => $slug,
        'short_desc' => $data['short_desc'] ?? null,
        'desc' => $data['desc'],
        'meta_type' => $data['meta_type'] ?? null,
        'meta_description' => $data['meta_description'] ?? null,
        'meta_keywords' => $data['meta_keywords'] ?? null,
        'status' => $data['status'] ?? 1, // Default to 1 (active)
        'image' => $imagePath, // Store the image path
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

        // Check if image is present in the data array and handle the file upload
        if (isset($data['image'])) {
            // If there's an existing image, delete it from storage
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            // Get the uploaded image from the data array
            $image = $data['image'];

            // Generate a unique filename
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '-' . uniqid() . '.' . $extension;

            // Store the image in the 'blogs' directory on the public disk
            $imagePath = $image->storeAs('blogs', $filename, 'public');
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
            'short_desc' => $data['short_desc'] ?? null,
            'desc' => $data['desc'],
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
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return true;
    }
}
