<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\PartnerRepositoryInterface;
use Illuminate\Support\Facades\DB;
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
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', 

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
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', 
        ]);
    
        $this->partnerRepository->update($id, $request->all());
        return redirect()->route('admin.partners.list.all')->with('success', 'Partner updated successfully.');
    }

    public function delete(Request $request, $id)
    {
         //dd($id);
         $this->partnerRepository->delete($id);
         return redirect()->route('admin.partners.list.all')->with('success', 'Partner deleted successfully.');
    }

}
