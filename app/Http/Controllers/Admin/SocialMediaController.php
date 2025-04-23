<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\SocialRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\SocialMedia;

class SocialMediaController extends Controller
{
    private $socialRepository;

    public function __construct(SocialRepositoryInterface $socialRepository){
        $this->socialRepository = $socialRepository;
    }

    public function index(Request $request)
    {
        $keyword    = $request->keyword ?? '';
        $query      = SocialMedia::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);

        return view('admin.social.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.social.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => ['required',
                'string',
                'max:255',
                Rule::unique('social_media', 'title'),
            ],
            'image'         => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
            'social_link'   => 'nullable|string',
        ], [
            'title.required'    => 'The title field is required.',
            'title.string'      => 'The title must be a valid string.',
            'title.max'         => 'The title cannot exceed 255 characters.',
            'title.unique'      => 'This title already exists. Please use a different one.',
        
            'image.required'    => 'Please upload an image.',
            'image.image'       => 'The uploaded file must be an image.',
            'image.mimes'       => 'The image must be a type of: jpg, jpeg, png, webp, gif, or svg.',
            'image.max'         => 'The image must not be larger than 1MB.',
        
            'social_link.string' => 'The social link must be a valid string.',
        ]);

        $data = $request->all();
        //Image upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $file       = $request->file('image');
            $extension  = $file->extension();
            $fileName   = time(). rand(10000,99999) . '.' . $extension;
            //Ensure we store only the relative path in the database
            $filePath   = 'uploads/social/' . $fileName;
            //Move file only once
            $file->move(public_path('uploads/social/'), $fileName);
            $data['image'] = $filePath;
        }

        $this->socialRepository->create($data);
        return redirect()->route('admin.social_media.list.all')->with('success', 'New Social media created');
    }

    public function edit($id)
    {
        $data = $this->socialRepository->findById($id);
        return view('admin.social.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:1000',
        //     'social_link' => 'required|string',
        // ], [
        //     'image.max' => 'The image must not be greater than 1MB.',
        // ]);

        // $social = SocialMedia::findOrFail($request->id);
        // $social->title = $request->title;
        // $social->link = $request->social_link;

        // // image upload
        // if (isset($request->image)) {
        //     $fileUpload = fileUpload($request->image, 'social');

        //     $social->image = $fileUpload['file'][0];
        // }

        // $social->save();

        // return redirect()->route('admin.social_media.list.all')->with('success', 'Social media updated');
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('social_media', 'title')->ignore($id),
            ],
            'social_link'   => 'nullable|string',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ],
        [
            'title.required'    => 'The social media title is required.',
            'title.string'      => 'The title must be a valid string.',
            'title.max'         => 'The title cannot exceed 255 characters.',
            'title.unique'      => 'This title already exists. Please choose a different one.',
    
            'social_link.required'  => 'The social media link is required.',
            'social_link.string'    => 'The link must be a valid string.',
    
            'image.image'   => 'The uploaded file must be an image.',
            'image.mimes'   => 'The image must be in one of the following formats: jpeg, png, jpg, gif, svg, webp.',
            'image.max'     => 'The image size must not exceed 2MB.',
        ]);
    
        $this->socialRepository->update($id, $request->all());
        return redirect()->route('admin.social_media.list.all')->with('success', 'Social media updated successfully.');
    }

    public function delete(Request $request) {
        // Get SocialMedia data by ID
        $socialMedia = SocialMedia::findOrFail($request->id);
        // If SocialMedia is not found then return status 404 with error message
        if (!$socialMedia) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Social Media is not found',
            ]);
        }
        $imagePath = $socialMedia->image;
        // Delete SocialMedia from db
        $socialMedia->delete();
        // If file is exist ithen remove from target directory
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
        // Return suceess response with message
        return response()->json([
            'status'    => 200,
            'message'   => 'Social Media has been deleted successfully',
        ]);
    }
}
