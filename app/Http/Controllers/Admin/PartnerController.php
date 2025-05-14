<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\PartnerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Partner;

class PartnerController extends Controller
{
    //
    private $partnerRepository;

    public function __construct(PartnerRepositoryInterface $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
    }

    public function index(Request $request)
    {
        $keyword    = $request->keyword ?? '';
        $query      = Partner::query();
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });

        $data = $query->latest('id')->paginate(25);
        return view('admin.partners.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required',
            'string',
            'max:255',
            Rule::unique('partners', 'title'),
        ],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', 

        ],[
            'title.required'   => 'The partner title is required.',
            'title.string'     => 'The title must be a valid string.',
            'title.max'        => 'The title cannot exceed 255 characters.',
            'title.unique'     => 'This title already exists. Please choose a different one.',
        
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be in one of the following formats: jpeg, png, jpg, gif, svg, webp.',
            'image.max'   => 'The image size must not exceed 5MB.',
        ]);
    
        $data = $request->all();    
        // Handle Image Upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file       = $request->file('image');
            $extension  = $file->extension(); 
            $fileName   = time() . rand(10000, 99999) . '.' . $extension;           
            // Ensure we store only the relative path in the database
            $filePath   = 'uploads/partners/' . $fileName;   
            // Move file only once
            $file->move(public_path('uploads/partners'), $fileName);
    
            $data['image'] = $filePath; // Store the relative path in the DB
        }
    
        // Save data using the repository
        $this->partnerRepository->create($data);   
        return redirect()->route('admin.partners.list.all')->with('success', 'Partner created successfully.');    
    }

    public function edit($id)
    {
        $data = $this->partnerRepository->findById($id);
        return view('admin.partners.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required',
                'string',
                'max:255',
                Rule::unique('partners', 'title')->ignore($id),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', 
        ],
        [
            'title.required' => 'The partner title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title cannot exceed 255 characters.',
            'title.unique' => 'This title already exists. Please choose a different one.',
        
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be in one of the following formats: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'The image size must not exceed 2MB.',
        ]);
    
        $this->partnerRepository->update($id, $request->all());
        return redirect()->route('admin.partners.list.all')->with('success', 'Partner updated successfully.');
    }

    public function delete(Request $request) {
        // Get partner data by ID
        $partner = Partner::findOrFail($request->id);
        // If partner is not found then return status 404 with error message
        if (!$partner) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Partner is not found',
            ]);
        }
        $imagePath = $partner->image;
        // Delete partner from db
        $partner->delete();
        // If file is exist ithen remove from target directory
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
        // Return suceess response with message
        return response()->json([
            'status'    => 200,
            'message'   => 'Partner has been deleted successfully',
        ]);
    }
    
}
