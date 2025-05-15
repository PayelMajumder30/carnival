<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\BannerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;

class BannerController extends Controller
{
    //
    private $bannerRepository;
    public function __construct(BannerRepositoryInterface $bannerRepository){
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = Banner::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $data = $query->latest('id')->paginate(25);
        return view('admin.banner.index', compact('data'));
    }

    public function create(Request $request)
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {

      $request->validate([
        'title'=> 'required|string|max:255',
      ],[
        'title.required' => 'The banner title is required.',
        'title.string' => 'The banner title must be a valid string.',
        'title.max' => 'The banner title cannot exceed 255 characters.',
      ]);

      //delete the existing banner
      $existingBanner = Banner::first();
      if($existingBanner){
        $existingBanner->delete();
      }

      //create new banner
      $data = $request->only('title'); //only - It restricts the data to just the 'title' field.
      $this->bannerRepository->create($data);

      return redirect()->route('admin.banner.list.all')->with('success', 'Banner updated successfully');
    }

    public function edit($id){
        $data = $this->bannerRepository->findById($id);
        return view('admin.banner.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $this->bannerRepository->update($id, $request->all());
        return redirect()->route('admin.banner.list.all')->with('success', 'Banner title updated successfully.');
    }

    public function delete(Request $request){
        $banner = Banner::find($request->id); // use find(), not findOrFail() to avoid immediate 404
    
        if (!$banner) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Banner not found.',
            ]);
        }
    
        $banner->delete(); // perform deletion
        return response()->json([
            'status'    => 200,
            'message'   => 'Banner deleted successfully.',
        ]);
    }

}
