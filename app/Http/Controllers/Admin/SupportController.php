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




    
    public function edit($id){
        $data = $this->SupportRepository->findById($id);
        return view('admin.support.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description'  => 'required|string|min:1',
        ]);

        $id = $request->id; 
        $this->SupportRepository->update($id, $request->only(['title', 'description']));

        return redirect()->route('admin.support.list.all')->with('success', 'Support updated successfully');
    }

    public function status(Request $request, $id)
    {
        $data = Support::find($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }
}
