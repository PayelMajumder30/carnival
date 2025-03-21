<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\ChooseUsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\WhyChooseUs;

class WhyChooseController extends Controller
{
    //

    private $chooseUsRepository;
    public function __construct(ChooseUsRepositoryInterface $chooseUsRepository){
        $this->chooseUsRepository = $chooseUsRepository;
    }

    public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = WhyChooseUs::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%')
                ->orWhere('desc', 'like', '%'.$keyword. '%');
        });
        $data = $query->latest('id')->paginate(25);
        return view('admin.whychooseus.index', compact('data'));
    }
    public function create(Request $request){
        return view('admin.whychooseus.create');
    }
    public function store(Request $request){
        $request->validate([
            'title'     => 'required|string|max:255',
            'desc'      => 'required|string|min:1',
        ]);

        $data = $request->all();
        $this->chooseUsRepository->create($data);
        return redirect()->route('admin.whychooseus.list.all')->with('success', 'New why choose us pannel created');
    }

    public function edit($id){
        $data = $this->chooseUsRepository->findById($id);
        return view('admin.whychooseus.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'title' => 'required|string|max:255',
            'desc'  => 'required|string|min:1',
        ]);

        $this->chooseUsRepository->update($id, $request->all());
        return redirect()->route('admin.whychooseus.list.all')->with('success', 'Why choose us updated successfully');
    }

    public function delete(Request $request, $id){
        $this->chooseUsRepository->delete($id);
        return redirect()->route('admin.whychooseus.list.all')->with('success', 'Why choose us deleted');
    }
}
