<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{TripCategory, TripCategoryDestination, SocialMedia, Banner};

class ApiController extends Controller
{
    //

    //master module/ trip category
    public function tripIndex()
    {
        $data = TripCategory::orderBy('positions', 'asc')->get();
        return response()->json([
            'status'    => 200,
            'success'   => true,
            'data'      => $data
        ]);
    }

    public function tripShow($id)
    {
        $tripCategory = TripCategory::find($id);
        if (!$tripCategory) {
            return response()->json(['status' => 404, 'success' => false, 'message' => 'Not found']);
        }
        return response()->json(['status' => 200, 'success' => true, 'data' => $tripCategory]);
    }

    
    //master module/ trip category/ destination
    public function getDestinationsByTripCategory($trip_cat_id) {
       $tripCategory = TripCategory::find($trip_cat_id);

        if(!$tripCategory) {
        return response()->json([
            'status'    => 404,
            'success'   => false,
            'message'   => 'Trip category not found.',
            'data'      => [],
        ]);
       }

        $destinations = TripCategoryDestination::with('tripdestination')
            ->where('trip_cat_id', $trip_cat_id)
            ->where('status', 1)
            ->get();

        if($destinations->isEmpty()) {
            return response()->json([
                'status'    => 200,
                'success'   => false,
                'message'   => 'No destinations found for this trip category.',
                'data'      => []
            ]);
        }
        
        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Destinations fetched successfully.',
            'data'      => $destinations,
        ]);
    }


    //master module/ social media
    public function socialmediaIndex() {
        $data = SocialMedia::orderBy('id')->get();
        return response()->json([
            'status'    => 200,
            'success'   => true,
            'data'      => $data
        ]);
    }

    public function socialmediaShow($id) {
        $data = SocialMedia::find($id);
        if(!$data) {
            return response()->json(['status' => 404, 'success'=>false, 'message' => 'Not found']);
        }
        return response()->json(['status' => 200, 'success'=>true, 'data' => $data]);
    }


    //master module/ banner
    public function bannerIndex() {
        $data = Banner::orderBy('id')->get();
        return response()->json([
            'status'    => 200,
            'success'   => true,
            'data'      => $data
        ]);
    }

    public function bannerShow($id) {
        $data = Banner::find($id);

        if(!$data) {
            return response()->json(['status' => 404, 'success' => false, 'message' => 'Not found']);
        }
        return response()->json(['status' => 200, 'success' => true, 'data' => $data]);
    }
}


