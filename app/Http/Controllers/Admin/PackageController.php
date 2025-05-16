<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\DestiantionPackageInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\{PackageCategory};


class PackageController extends Controller
{

    //
    private $destiantionPackageRepository;
    public function __construct(DestiantionPackageInterface $destiantionPackageRepository){
        $this->destiantionPackageRepository = $destiantionPackageRepository;
    }

    //Destinationwise package Category

    public function packageCategoryIndex(Request $request)
    {
        $keyword = $request->input('keyword');
        $query = PackageCategory::query(); // now used as a global package category

        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        $packageCategories = $query->latest('id')->paginate(25);

        return view('admin.packageCategory.index', compact('packageCategories'));
    }

    public function packageCategoryCreate($id)
    {
        $packageCategories  = Destination::findOrFail($id);
        return view('admin.destination.packageCategoryIndex', compact('packageCategories'));
    }

    public function packageCategoryStore(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255|unique:package_category,title',
        ],[
            'title.required' => 'The title is required.',
            'title.string'   => 'The title must be a valid string.',
            'title.max'      => 'The title cannot exceed 255 characters.',
            'title.unique'   => 'This title already exists. Please choose a different one.',
        ]);

        $this->destiantionPackageRepository->create([
            'title' => $request->title,
    ]);

        // Redirect to the generic package category listing route (adjust route name if needed)
        return redirect()->route('admin.packageCategory.list.all') ->with('success', 'New Title created');
    }


    public function packageCategoryUpdate(Request $request) {
        $request->validate([
            'id' => 'required|exists:package_category,id',
            'title' => 'required|string|max:255|unique:package_category,title,' . $request->id,
        ]);

        $category = PackageCategory::findOrFail($request->id);
        $category->title = $request->title;
        $category->save();

        return redirect()->back()->with('success', 'Package category title updated successfully.');
    }

    public function packageCategoryStatus(Request $request, $id)
    {
        $data = PackageCategory::find($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }

    public function packageCategoryDelete(Request $request){
        $package = PackageCategory::find($request->id); // use find(), not findOrFail() to avoid immediate 404    
        if (!$package) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Package not found.',
            ]);
        }
    
        $package->delete(); // perform deletion
        return response()->json([
            'status'    => 200,
            'message'   => 'Destinationwise package deleted successfully.',
        ]);
    }

 
}

    

