<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\SupportRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Support;

class SupportController extends Controller
{
     private $SupportRepository;
    public function __construct(SupportRepositoryInterface $SupportRepository){
        $this->SupportRepository = $SupportRepository;
    }

        public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = Support::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $data = $query->latest('id')->paginate(25);
        return view('admin.support.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.support.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title'    => 'required|string|max:255|unique:supports,title',
            'description' => 'nullable|string|max:255',
        ],[
            'title.required'    => 'The support title is required.',
            'title.string'      => 'The support title must be a valid string.',
            'title.max'         => 'The supportr title cannot exceed 255 characters.',
            'title.unique'      => 'This support title already exists. Please choose a different one.',
            'description.max'   => 'The description may not be greater than 255 characters.',
        ]);

        $data = $request->all();
        $this->SupportRepository->create($data);
        return redirect()->route('admin.support.list.all')->with('success', 'New support created');
    }

    public function delete(Request $request){
        $support = Support::find($request->id); // use find(), not findOrFail() to avoid immediate 404
    
        if (!$support) {
            return response()->json([
                'status'    => 404,
                'message'   => 'support not found.',
            ]);
        }
    
        $support->delete(); // perform deletion
        return response()->json([
            'status'    => 200,
            'message'   => 'support deleted successfully.',
        ]);
    }

}
