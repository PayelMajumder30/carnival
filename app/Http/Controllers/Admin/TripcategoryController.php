<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Interfaces\TripCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\{TripCategory, TripCategoryBanner};

class TripcategoryController extends Controller
{
    //
    private $TripCategoryRepository;
    public function __construct(TripCategoryRepositoryInterface $TripCategoryRepository){
        $this->TripCategoryRepository = $TripCategoryRepository;
    }

    public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = TripCategory::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $data = $query->orderBy('positions', 'asc')->paginate(5);
        return view('admin.tripcategory.index', compact('data'));
    }
    
    public function create(Request $request)
    {
        return view('admin.tripcategory.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title'    => 'required|string|max:255',
        ]);

        $data = $request->all();
        $this->TripCategoryRepository->create($data);
        return redirect()->route('admin.tripcategory.list.all')->with('success', 'New trip category created');
    }

    public function edit($id){
        $data = $this->TripCategoryRepository->findById($id);
        return view('admin.tripcategory.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $this->TripCategoryRepository->update($id, $request->all());
        return redirect()->route('admin.tripcategory.list.all')->with('success', 'Trip category title updated successfully.');
    }

    public function status(Request $request, $id) {
        $data = TripCategory::find($id);
        $data->status   = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'status updated',
        ]);
    }

    public function delete(Request $request, $id){
        $this->TripCategoryRepository->delete($id);
        return redirect()->route('admin.tripcategory.list.all')->with('success', 'Trip category deleted successfully.');
    }

    public function sort(Request $request) {
        foreach ($request->order as $item) {
            TripCategory::where('id', $item['id'])->update(['positions' => $item['position']]);
        }

        return response()->json([
            'status'    => 200,
            'message'   => 'Table updated successfully',
        ]);
    }

    public function bannerIndex(Request $request, $trip_cat_id) {
       // $trip = TripCategory::where('id',$trip_cat_id)->first();
        // dd($trip);

        $trip = TripCategory::findOrFail($trip_cat_id);
        $banner = TripCategoryBanner::where('trip_cat_id', $trip_cat_id)->paginate(10);
        return view('admin.tripcategory.bannerindex', compact('trip', 'banner'));
    }

    public function bannerCreate($trip_cat_id) {
        
        $trip  = TripCategory::findOrFail($trip_cat_id);
        return view('admin.tripcategory.bannercreate', compact('trip'));
    }

    public function bannerStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', 

        ]);
    
        $data = $request->all(); 
        $data['trip_cat_id'] = $request->trip_cat_id;   
        // Handle Image Upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file       = $request->file('image');
            $extension  = $file->extension(); 
            $fileName   = time() . rand(10000, 99999) . '.' . $extension;           
            // Ensure we store only the relative path in the database
            $filePath   = 'uploads/trip_category_banner/' . $fileName;   
            // Move file only once
            $file->move(public_path('uploads/trip_category_banner'), $fileName);
    
            $data['image'] = $filePath; // Store the relative path in the DB
        }
    
        // Save data using the repository
        $this->TripCategoryRepository->banner_create($data);   
        return redirect()->back()->with('success', 'Trip category Banner created successfully.');    
    }

    public function bannerEdit($banner_id) {
        $edit   = TripCategoryBanner::findOrFail($banner_id);
        return view('admin.tripcategory.banneredit', compact('edit'));
    }

    public function bannerUpdate(Request $request) {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', 
        ]);
        $data = $request->all();
    
        $this->TripCategoryRepository->banner_update($data);
        return redirect()->route('admin.tripcategorybanner.list.all', $request->trip_cat_id)->with('success', 'Trip category banner updated successfully.');
    }

    public function bannerStatus($id) {
        $data = TripCategoryBanner::find($id);
        $update = TripCategoryBanner::where('trip_cat_id', $data->trip_cat_id)->where('id', '!=', $id)->get();
        foreach($update as $k=>$item){
            $item_update = TripCategoryBanner::find($item->id);
            $item_update->status = 0;
            $item_update->save();
        }
        
        $data->status   = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'status updated',
        ]);
    }
}
