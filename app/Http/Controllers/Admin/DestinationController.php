<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Destination;

class DestinationController extends Controller
{
    public function index()
    {

        $Url = env('CRM_BASEPATH').'api/crm/active/country';
            $ch = curl_init($Url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);;

            $countryResponse = curl_exec($ch);
            curl_close($ch);

            $countryData = json_decode($countryResponse, true);
            if ($countryData['status']==true) {
                $new_country = $countryData['data'];
            } else {
                $new_country = [];
            }
            
        $data = Country::orderBy('country_name', 'ASC')->get();
        $existing_country = $data->pluck('crm_country_id')->toArray();

        return view('admin.destination.index', compact('data','new_country', 'existing_country'));
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

    public function destinationAdd(Request $request)
    {
        $validated = $request->validate([
            'crm_destination_id' => 'required|unique:destinations,crm_destination_id',
            'destination_name' => 'required|string|max:255',
        ]);

        Destination::create([
            'crm_destination_id' => $validated['crm_destination_id'],
            'destination_name' => $validated['destination_name'],
            'status' => 1
        ]);

        session()->flash('success', 'Destination added successfully');
        return response()->json(['success'=> true]);
    }
}
