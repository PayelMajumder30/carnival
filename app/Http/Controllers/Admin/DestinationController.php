<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Destination;


class DestinationController extends Controller
{
    public function index(Request $request)
    {

        $Url = env('CRM_BASEPATH').'api/crm/active/country';
        $ch = curl_init($Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $countryResponse = curl_exec($ch);
        curl_close($ch);
        $countryData = json_decode($countryResponse, true);
        //dd(env('CRM_BASEPATH'));
        if ($countryData['status']==true) {
            $new_country = $countryData['data'];
        } else {
            $new_country = [];
        }
       // dd($new_country);
       
       $showCountry = Country::select('country_name')
        ->orderBy('country_name')
        ->pluck('country_name');

        $country_filter = $request->input('country_filter');
        $keyword = $request->input('keyword');

        if ($country_filter) {
            $data = Country::where('country_name', 'like', '%' . $country_filter . '%')
                ->orderBy('country_name', 'ASC')
                ->get();
        } elseif ($keyword) {
            $data = Country::select('countries.*')
            ->whereHas('destinations', function($query) use ($keyword) {
                $query->where('destination_name', 'like', '%' . $keyword . '%');
            })
            ->orWhere('countries.country_name', 'like', '%' . $keyword . '%')
            ->with('destinations') 
            ->orderBy('countries.country_name', 'ASC')
            ->get();
        } else {
            $data = Country::orderBy('country_name', 'ASC')->get();
        }

        $existing_country = $data->pluck('crm_country_id')->toArray();

        return view('admin.destination.index', compact('new_country', 'existing_country', 'data', 'showCountry'));
    }

    public function show(Request $request)
    {
        $showCountry = Country::where('status',1)
        ->orderBy('country_name')
        ->pluck('country_name', 'id');
        return view('admin.destination.index',compact('showCountry'));
    }

    public function countryAdd(Request $request)
    {
        $validated = $request->validate([
            'crm_country_id' => 'required|unique:countries,crm_country_id',
            'country_name' => 'required|string|max:255',
        ]);
    
        Country::create([
            'crm_country_id' => $validated['crm_country_id'],
            'country_name' => $validated['country_name'],
            'status' => 1,
        ]);

        session()->flash('success', 'Country Added Successfully');
        return response()->json(['success' => true]);
    }

    public function countryStatus(Request $request, $id) {
        $data = Country::find($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }


    // Country wise Destination //

    public function destinationAdd(Request $request)
    {
        $validated = $request->validate([
            'crm_destination_id' => 'required|unique:destinations,crm_destination_id',
            'destination_name' => 'required|string|max:255',
        ]);

        Destination::create([
            'country_id' => $request->country_id,
            'crm_destination_id' => $validated['crm_destination_id'],
            'destination_name' => $validated['destination_name'],
            'status' => 1
        ]);

        session()->flash('success', 'Destination added successfully');
        return response()->json(['success'=> true]);
    }

   
    public function createDestImage(Request $request) {
        $request->validate([
            'id'    => 'required|exists:destinations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'image.max'   => 'Upload image must not be more than 2MB.',
            'logo.max'    => 'Logo must not be more than 2MB.',
        ]);

        if (!$request->hasFile('image') && !$request->hasFile('logo')) {
            return response()->json([
                'errors' => [
                    'image' => ['Please upload at least one file: image or logo.'],
                    'logo'  => ['Please upload at least one file: image or logo.'],
                ]
            ], 422);
        }

        $destination = Destination::find($request->id);


        //image and logo upload
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $image = $request->file('image');
            $imageName = time(). rand(10000, 99999). '.'. $image->extension();
            $imagePath = 'uploads/country_wise_dest/' . $imageName;

            $image->move(public_path('uploads/country_wise_dest'), $imageName);
            $destination->image = $imagePath;
        }

        if($request->hasFile('logo') && $request->file('logo')->isValid())
        {
            $logo = $request->file('logo');
            $logoName = time(). rand(10000, 99999). '_logo.'. $logo->extension();
            $logoPath = 'uploads/country_wise_dest/' . $logoName;

            $logo->move(public_path('uploads/country_wise_dest'), $logoName);
            $destination->logo = $logoPath;
        }

        $destination->save();


        // return redirect()->back()->with('success', 'Image and logo Updated Successfully');
        return response()->json(['success' => true]);
    }

    public function destinationStatus(Request $request, $id) {      
        $destination = Destination::find($id);
        if (!$destination) {
            return response()->json([
                'status'  => 404,
                'message' => 'Destination not found',
            ]);
        }
        // Toggle status
        $destination->status = $destination->status == 1 ? 0 : 1;
        $destination->save();
    
        return response()->json([
            'status'  => 200,
            'message' => 'Status updated',
        ]);
    }

    public function destinationDelete(Request $request) {
        $countryDestination = Destination::findOrFail($request->id);
        if(!$countryDestination) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Countrywise destination not found',
            ]);
        }
        $imagePath = $countryDestination->image;
        $logoPath = $countryDestination->logo;
        // Delete banner from db
        $countryDestination->delete();
        // If file is exist then remove from target directory
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }

        if (!empty($logoPath) && file_exists(public_path($logoPath))) {
            unlink(public_path($logoPath));
        }
        // Return suceess response with message
        return response()->json([
            'status'    => 200,
            'message'   => 'Countrywise destination has been deleted successfully',
        ]);
    }
 
}

    

