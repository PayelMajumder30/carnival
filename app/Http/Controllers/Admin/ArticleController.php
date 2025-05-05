<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ArticleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Article;

class ArticleController extends Controller
{
    private $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $keyword    = $request->keyword ?? '';
        $query      = Article::query();
        
        // Apply search filter based on the keyword (search in title or content)
        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%')
                  ->orWhere('content', 'like', '%'.$keyword.'%')
                  ->orWhere('sub_title', 'like', '%'.$keyword.'%');
        });
    
        // Paginate results
        $articles = $query->latest('id')->paginate(25);
    
        return view('admin.article.index', compact('articles'));
    }
    

    public function create()
    {
        return view('admin.article.add');
    }

    public function store(Request $request)
    {
    $request->validate([
    'title' => 'required|string|max:255',
    'sub_title' => 'nullable|string|max:255',
    'content' => 'required|string',
    'meta_type' => 'nullable|string',
    'meta_description' => 'nullable|string',
    'meta_keywords' => 'nullable|string',
    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
    ]);
   
    // Prepare the request data
    $data = $request->all();
    // Handle Image Upload
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
    $file = $request->file('image');
    $extension = $file->extension(); // Alternative to getClientOriginalExtension()
    $fileName = time() . rand(10000, 99999) . '.' . $extension;
    $filePath = 'uploads/articles/' . $fileName; // Save path in DB
   
    // Move file to public/uploads/articles directory
    $file->move(public_path('uploads/articles'), $fileName);
   
    // Add image path to data before sending it to the repository
    $data['image'] = $filePath;
    }
   
    $this->articleRepository->create($data);
    return redirect()->route('admin.article.list.all')->with('success', 'Article created successfully.');
    //dd($request->all()); 
    }

    public function edit($id)
    {
        $data = $this->articleRepository->findById($id);
        return view('admin.article.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd("dgfcdysgy");
        $request->validate([
            'title'             => 'required|string|max:255',
            'sub_title'         => 'nullable|string|max:255',
            'content'           => 'required|string',
            'meta_type'         => 'nullable|string',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);
    
        $this->articleRepository->update($id, $request->all());
        return redirect()->route('admin.article.list.all')->with('success', 'Article updated successfully.');
    }

    public function ArticleStatus(Request $request, $id)
    {
        $data = $this->articleRepository->findById($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }
    
    public function delete($id)
    {
        //dd($id);
        $this->articleRepository->delete($id);
        return redirect()->route('admin.article.list.all')->with('success', 'Article deleted successfully.');
    }
}
