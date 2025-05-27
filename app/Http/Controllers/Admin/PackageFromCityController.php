<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\PackageInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\{Destination, PackagesFromTopCities, Country};


class PackageFromCityController extends Controller
{

    public function index(Request $request) {
        $keyword = $request->input('keyword');
        $selectedDestinationId = $request->input('destination_id');

        // Fetch all active destinations from country_id 10
        $destinations = Destination::select('id', 'destination_name')
            ->where('country_id', 10)
            ->where('status', 1)
            ->get();

        // Fetch assigned cities with optional filters
        $query = PackagesFromTopCities::with('destination');

        // Filter by destination if selected
        if ($selectedDestinationId) {
            $query->where('destination_id', $selectedDestinationId);
        }

        // Filter by keyword: title, city, or destination name
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('city', 'like', '%' . $keyword . '%')
                ->orWhereHas('destination', function ($q2) use ($keyword) {
                    $q2->where('destination_name', 'like', '%' . $keyword . '%');
                });
            });
        }

        $assignedCities     = $query->latest('id')->paginate(25);
        $assignedCityNames  = $assignedCities->pluck('city')->toArray();

        return view('admin.packageFromtopCity.index', compact(
            'destinations',
            'selectedDestinationId',
            'assignedCities',
            'assignedCityNames'
        ));
    }

    public function store(Request $request) {

        // Validate required inputs
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'indian_cities' => 'required|string',
        ]);

        $existCity = PackagesFromTopCities::where('destination_id', $request->destination_id)
                ->where('city', $request->indian_cities)
                ->exists();

        if($existCity) {
            return redirect()->back()->with('error', 'This city is already assigned to the selected destination.');
        }

        $destination = Destination::find($request->destination_id);
        $title = $destination->destination_name.' packages from ' .$request->indian_cities;
        $slug = Str::slug($title);

        PackagesFromTopCities::create([
            'destination_id' => $request->destination_id,
            'city'      => $request->indian_cities,
            'title'     => $title,
            'slug'      => $slug,
        ]);

        return redirect()->route('admin.assignCitytoPackage.index')->with('success', 'Assigned successfully.');
    }

    public function status(Request $request, $id)
    {
        $data = PackagesFromTopCities::find($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }

    public function delete(Request $request){
        $data = PackagesFromTopCities::find($request->id); // use find(), not findOrFail() to avoid immediate 404
    
        if (!$data) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Not found.',
            ]);
        }
    
        $data->delete(); // perform deletion
        return response()->json([
            'status'    => 200,
            'message'   => 'Deleted successfully.',
        ]);
    }

}