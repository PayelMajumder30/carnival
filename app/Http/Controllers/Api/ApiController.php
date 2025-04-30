<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TripCategory;

class ApiController extends Controller
{
    //

    public function index()
    {
        $data = TripCategory::orderBy('positions', 'desc')->get();
        return response()->json([
            'status' => 200,
            'success' => $data->isNotEmpty(),
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $tripCategory = TripCategory::find($id);
        if (!$tripCategory) {
            return response()->json(['status' => 404, 'message' => 'Not found']);
        }
        return response()->json(['status' => 200, 'data' => $tripCategory]);
    }


}
