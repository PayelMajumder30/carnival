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
}
