<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsLetter;


class NewsletterController extends Controller
{
    //
    public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = NewsLetter::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('email', 'like', '%'.$keyword.'%');
                
        });
        $data = $query->latest('id')->paginate(25);
        return view('admin.newsletter.index', compact('data'));
    }
}
