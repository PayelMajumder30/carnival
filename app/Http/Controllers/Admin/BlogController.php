<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\BlogRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    private $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $query = Blog::query();
        
        // Apply search filter based on the keyword (search in title or desc)
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%')
                  ->orWhere('desc', 'like', '%'.$keyword.'%');
        });
    
        // Paginate results
        $blogs = $query->latest('id')->paginate(25);
    
        return view('admin.blog.index', compact('blogs'));
    }
    

    public function create()
    {
        return view('admin.blog.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
                'short_desc' => 'nullable|string|max:255',
                'desc' => 'required|string',
                'meta_type' => 'nullable|string',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);
        

        $this->blogRepository->create($request->all());
        return redirect()->route('admin.blog.list.all')->with('success', 'Blog created successfully.');
    }

    public function show($id)
    {
        $blog = $this->blogRepository->findById($id);
        return view('admin.blog.show', compact('blog'));
    }

    public function edit($id)
    {
        $data = $this->blogRepository->findById($id);
        return view('admin.blog.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd("dgfcdysgy");
        $request->validate([
            'title' => 'required|string|max:255',
            'short_desc' => 'nullable|string|max:255',
            'desc' => 'required|string',
            'meta_type' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);
    
        $this->blogRepository->update($id, $request->all());
        return redirect()->route('admin.blog.list.all')->with('success', 'Blog updated successfully.');
    }

    public function BlogStatus(Request $request, $id)
    {
        $data = $this->blogRepository->findById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status' => 200,
            'message' => 'Status updated',
        ]);
    }
    
    public function delete($id)
    {
        $this->blogRepository->delete($id);
        return redirect()->route('admin.blog.list.all')->with('success', 'Blog deleted successfully.');
    }
}
