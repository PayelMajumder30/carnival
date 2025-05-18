<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\PackageInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\{PackageCategory};


class PackageController extends Controller
{
    //
    private $packageRepository;
    public function __construct(PackageInterface $packageRepository){
        $this->packageRepository = $packageRepository;
    }

    public function index(Request $request) {
        $keyword    = $request->keyword;
        $query      = PackageCategory::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $data = $query->latest('id')->paginate(25);
        return view('admin.packageCategory.index', compact('data'));
    }

    public function create(Request $request) {
        return view ('admin.packageCategory.index');
    }
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255|unique:package_category,title',
        ],[
            'title.required'    => 'The title is required.',
            'title.string'      => 'The title must be a valid string.',
            'title.max'         => 'The  title cannot exceed 255 characters.',
            'title.unique'      => 'This title already exists. Please choose a different one.',
        ]);
        $data = $request->all();
        $this->packageRepository->create($data);
        return redirect()->route('admin.packageCategory.list.all')->with('success', 'New Package category created');
    }

    public function status(Request $request, $id)
    {
        $data = PackageCategory::find($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }

    public function update(Request $request) {
        //dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $id = $request->id; 
        $this->packageRepository->update($id, $request->only(['title']));
        
        return redirect()->route('admin.packageCategory.list.all')->with('success', 'Package Category updated successfully');
    }

    public function delete(Request $request){
        $data = PackageCategory::find($request->id); // use find(), not findOrFail() to avoid immediate 404
    
        if (!$data) {
            return response()->json([
                'status'    => 404,
                'message'   => 'package category not found.',
            ]);
        }
    
        $data->delete(); // perform deletion
        return response()->json([
            'status'    => 200,
            'message'   => 'package category deleted successfully.',
        ]);
    }

 
}

    

