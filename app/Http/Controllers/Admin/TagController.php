<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Interfaces\TaglistRepositoryInterface;
use Illuminate\Validation\Rule;
use App\Models\{TagList};

class TagController extends Controller
{
    //
    private $TaglistRepository;
    public function __construct(TaglistRepositoryInterface $TaglistRepository){
        $this->TaglistRepository = $TaglistRepository;
    }

    public function index(Request $request) {
        $keyword    = $request->keyword;
        $query      = TagList::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $data = $query->latest('id')->paginate(25);
        return view('admin.tag.list', compact('data'));
    }

    public function create(Request $request) {
        return view ('admin.tag.list');
    }
    public function store(Request $request) {
        $request->validate([
            'title' => [
            'required',
            'string',
            'max:255',
                Rule::unique('tag_list')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
        ],[
            'title.required'    => 'The title is required.',
            'title.string'      => 'The title must be a valid string.',
            'title.max'         => 'The  title cannot exceed 255 characters.',
            'title.unique'      => 'This title already exists. Please choose a different one.',
        ]);
        $data = $request->all();
        $this->TaglistRepository->create($data);
        return redirect()->route('admin.tag.list.all')->with('success', 'New Tag created');
    }

    public function status(Request $request, $id)
    {
        $data = TagList::find($id);
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
        $this->TaglistRepository->update($id, $request->only(['title']));
        
        return redirect()->route('admin.tag.list.all')->with('success', 'Tag updated successfully');
    }

    public function delete(Request $request){
       $item = TagList::findOrFail($request->id);

       if($item->delete()) {
        return response()->json(['status' => 'success', 'message' => 'Tag deleted successfully.']);
       } else {
        return response()->json(['status' => 'error', 'message' => 'Deletion failed.']);
       }
    }

 
}

