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
}
