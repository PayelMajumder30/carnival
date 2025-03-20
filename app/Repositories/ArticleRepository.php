<?php

namespace App\Repositories;

use App\Models\Article;
use App\Interfaces\ArticleRepositoryInterface;
use Auth;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getAll()
    {
        return Article::all();
    }

    public function findById($id)
    {
        return Article::findOrFail($id);
    }

    public function create(array $data)
        {
            // Generate the slug based on the title
            $slug = slugGenerate($data['title'], 'articles');

            // Initialize image path to null
            $imagePath = $data['image'] ?? null;

            // // Check if the image exists in the data array
            // if (isset($data['image'])) {
            //     $image = $data['image']; // Get the image from the data array
            //     $extension = $image->getClientOriginalExtension(); // Get the file extension
            //     $filename = time() . '-' . uniqid() . '.' . $extension; // Generate a unique filename
            //     $imagePath = $image->storeAs('articles', $filename, 'public'); // Store the image
            // }

            // Prepare the article data
            $articleData = [
                'admin_id'          => auth()->id(),  // Assuming the admin is logged in
                'title'             => $data['title'],
                'slug'              => $slug,
                'sub_title'         => $data['sub_title'] ?? null,
                'content'           => $data['content'],
                'meta_type'         => $data['meta_type'] ?? null,
                'meta_description'  => $data['meta_description'] ?? null,
                'meta_keywords'     => $data['meta_keywords'] ?? null,
                'status'            => $data['status'] ?? 1, // Default to 1 (active)
                'image'             => $imagePath, // Store the image path
            ];

            // Create the article and return the created instance
            return Article::create($articleData);
        }


    public function update($id, array $data)
        {
            // // Find the article by ID
            // $article = Article::findOrFail($id);

            // // Retrieve the existing image path (if any)
            // $imagePath = $article->image;

            // // Check if image is present in the data array and handle the file upload
            // if (isset($data['image'])) {
            //     // If there's an existing image, delete it from storage
            //     if ($imagePath) {
            //         Storage::disk('public')->delete($imagePath);
            //     }

            //     // Get the uploaded image from the data array
            //     $image = $data['image'];

            //     // Generate a unique filename
            //     $extension = $image->getClientOriginalExtension();
            //     $filename = time() . '-' . uniqid() . '.' . $extension;

            //     // Store the image in the 'articles' directory on the public disk
            //     $imagePath = $image->storeAs('articles', $filename, 'public');
            // }

            // // Generate slug if it's not already provided
            // if (!isset($data['slug'])) {
            //     $data['slug'] = slugGenerateUpdate($data['title'], 'articles', $id);
            // }

            // // Prepare the data to update the article
            // $updateData = [
            //     'admin_id' => auth()->id(),
            //     'title' => $data['title'],
            //     'slug' => $data['slug'],
            //     'sub_title' => $data['sub_title'] ?? null,
            //     'content' => $data['content'],
            //     'meta_type' => $data['meta_type'] ?? null,
            //     'meta_description' => $data['meta_description'] ?? null,
            //     'meta_keywords' => $data['meta_keywords'] ?? null,
            //     'status' => $data['status'] ?? 1,
            //     'image' => $imagePath, // Add the image path
            // ];

            // // Update the article and return it
            // $article->update($updateData);

            // return $article;

            // Find the article by ID
            $article = Article::findOrFail($id);
            $imagePath = $article->image;
            if (isset($data['image']) && $data['image']->isValid()) {
                // If there's an existing image, delete it from public/uploads/articles
                if (!empty($imagePath) && file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
                $image      = $data['image'];
                $extension  = $image->getClientOriginalExtension();
                $filename   = time() . rand(10000, 99999) . '.' . $extension;
                $filePath   = 'uploads/articles/' . $filename;
                $image->move(public_path('uploads/articles'), $filename);
                $imagePath  = $filePath;
            }

            // Generate slug if it's not already provided
            if (!isset($data['slug'])) {
                $data['slug'] = slugGenerateUpdate($data['title'], 'articles', $id);
            }
            $updateData = [
                'admin_id'          => auth()->id(),
                'title'             => $data['title'],
                'slug'              => $data['slug'],
                'sub_title'         => $data['sub_title'] ?? null,
                'content'           => $data['content'],
                'meta_type'         => $data['meta_type'] ?? null,
                'meta_description'  => $data['meta_description'] ?? null,
                'meta_keywords'     => $data['meta_keywords'] ?? null,
                'status'            => $data['status'] ?? 1,
                'image'             => $imagePath, // Assign the image path
            ];
            $article->update($updateData);
            return $article;
        }

    public function delete($id)
    {
        $article = Article::findOrFail($id);
        //dd($article);
        //check if article has an image
        if($article->image && file_exists(public_path($article->image))) {
            unlink(public_path($article->image));
        }
        if($article->delete()){
            return true;
        } else{
            return false;
        }
        
    }
}
