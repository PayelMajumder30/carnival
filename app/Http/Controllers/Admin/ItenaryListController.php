<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\ItenarylistRepositoryInterface;
use App\Models\ItenaryList;

class ItenaryListController extends Controller
{
    private $ItenarylistRepository;
        public function __construct(ItenarylistRepositoryInterface $ItenarylistRepository){
        $this->ItenarylistRepository = $ItenarylistRepository;
    }


    public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = ItenaryList::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%')
                ->orWhere('short_description', 'like', '%'.$keyword.'%');
        });
        $data = $query->latest('id')->paginate(25);
        return view('admin.itenaries.list', compact('data'));
    }

    public function create()
    {
        return view('admin.itenaries.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
        'main_image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
        'title' => 'required|string|max:255|unique:itenary_list,title',
        'short_description' => 'nullable|string|max:255',
        'selling_price' => 'required|numeric|lte:actual_price',
        'actual_price' => 'required|numeric',
        ],[
        'title.required' => 'The title is required.',
        'title.string' => 'The title must be a valid string.',
        'title.max' => 'The title cannot exceed 255 characters.',
        'title.unique' => 'This title already exists. Please choose a different one.',
        'main_image.max' => 'The image should not be more than 5mb.',
        'short_description.max' => 'The description may not be greater than 255 characters.',
        'selling_price.lte' => 'Selling price must be equal to or less than the actual price.',
        ]);

        $data = $request->all();

        if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
            $image = $request->file('main_image');
            $imageName = time().rand(10000, 99999).'.'.$image->extension();
            $imagePath = 'uploads/itenaries_list/'.$imageName;
            $image->move(public_path('uploads/itenaries_list'), $imageName);

            $data['main_image'] = $imagePath;
        }


        $this->ItenarylistRepository->create($data);
        return redirect()->route('admin.itenaries.list.all')->with('success', 'New Itenaries created');
    }

    public function edit($id)
    {
        $itenary = $this->ItenarylistRepository->findById($id);
        return view('admin.itenaries.edit', compact('itenary'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
            'title' => 'required|string|max:255|unique:itenary_list,title,' . $id,
            'short_description' => 'nullable|string|max:255',
            'selling_price' => 'required|numeric|lte:actual_price',
            'actual_price' => 'required|numeric',
        ]);

        $itenary = $this->ItenarylistRepository->findById($id);
        $data = $request->all();

        if($request->hasFile('main_image') && $request->file('main_image')->isValid()) {

            if(!empty($itenary->main_image) && file_exists(public_path($itenary->main_image))){
                unlink(public_path($itenary->main_image));
            }

            $image = $request->file('main_image');
            $imageName = time() . rand(10000, 99999) . '.' . $image->extension();
            $imagePath = 'uploads/itenaries_list/' . $imageName;
            $image->move(public_path('uploads/itenaries_list'), $imageName);

            $data['main_image'] = $imagePath; 
        }
        
        $this->ItenarylistRepository->update($id, $data);

        return redirect()->route('admin.itenaries.list.all')->with('success', 'Itenary updated successfully.');
    }

    public function toggleStatus($id)
    {
        $data = ItenaryList::find($id);
        $data->status   = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'status updated',
        ]);
    }

    public function delete($id)
    {
        $itenary = $this->ItenarylistRepository->findById($id);
        if (!$itenary) {
            return response()->json(['status' => 404, 'message' => 'Itinerary not found']);
        }

        if(!empty($itenary->main_image) && file_exists(public_path($itenary->main_image))){
            unlink(public_path($itenary->main_image));
        }

        $this->ItenarylistRepository->delete($id);

        return response()->json(['status' => 'success', 'message' => 'Itenary Deleted Successfully']);
    }

}
