<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Interfaces\TripCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\TripCategory;

class TripcategoryController extends Controller
{
    //
    private $tripcatRepository;
    public function __construct(TripCategoryRepositoryInterface $tripcatRepository){
        $this->tripcatRepository = $tripcatRepository;
    }

    public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = TripCategory::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $data = $query->latest('id')->paginate(25);
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
        $this->tripcatRepository->create($data);
        return redirect()->route('admin.tripcategory.list.all')->with('success', 'New trip category created');
    }

    public function edit($id){
        $data = $this->tripcatRepository->findById($id);
        return view('admin.tripcategory.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $this->tripcatRepository->update($id, $request->all());
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
        $this->tripcatRepository->delete($id);
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
}
