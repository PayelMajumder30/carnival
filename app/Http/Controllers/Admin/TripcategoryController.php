<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Interfaces\TripCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\{TripCategory, TripCategoryBanner, TripCategoryDestination, Country, Destination, TripCategoryActivities};

class TripcategoryController extends Controller
{
    //
    private $TripCategoryRepository;
    public function __construct(TripCategoryRepositoryInterface $TripCategoryRepository){
        $this->TripCategoryRepository = $TripCategoryRepository;
    }

    public function index(Request $request){
        $keyword    = $request->keyword;
        $query      = TripCategory::query();

        $query->when($keyword, function($query) use ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        });
        $data = $query->orderBy('positions', 'asc')->paginate(25);
        $allTrips = TripCategory::orderBy('title')->get();
        return view('admin.tripcategory.index', compact('data', 'allTrips'));
    }
    
    public function create(Request $request)
    {
        return view('admin.tripcategory.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title'      => 'required|string|max:255|unique:trip_categories,title',
            'short_desc' => 'nullable|string|max:400',
        ], [
            'title.required' => 'The title field is required.',
            'title.string'   => 'The title must be a string.',
            'title.max'      => 'The title may not be greater than 255 characters.',
            'title.unique'   => 'This title already exists in the trip categories.',
        ]);

        $data = $request->all();
        $this->TripCategoryRepository->create($data);
        return redirect()->route('admin.tripcategory.list.all')->with('success', 'New trip category created');
    }

    public function edit($id){
        $data = $this->TripCategoryRepository->findById($id);
        return view('admin.tripcategory.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'title'      => 'required|string|max:255|unique:trip_categories,title,' . $id,
            'short_desc' => 'nullable|string|max:400',
        ], [
            'title.required' => 'The title field is required.',
            'title.string'   => 'The title must be a string.',
            'title.max'      => 'The title may not be greater than 255 characters.',
            'title.unique'   => 'This title already exists in the trip categories.',
        ]);

        $this->TripCategoryRepository->update($id, $request->all());
        return redirect()->route('admin.tripcategory.list.all')->with('success', 'Trip category updated successfully.');
    }

    public function status(Request $request, $id) {
        $data = TripCategory::find($id);
        $data->status   = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'status updated',
        ]);
    }


    public function updateHighlights(Request $request) {

        TripCategory::query()->update(['is_highlighted' => 0]);
        // Then update selected
        if ($request->has('trip_ids')) {
            TripCategory::whereIn('id', $request->trip_ids)->update(['is_highlighted' => 1]);
        }
        return response()->json(['status' => true, 'message' => 'Highlighted trips updated successfully.']);
    }

    public function updateHeaders(Request $request)
    {
        // Reset all
        TripCategory::query()->update(['is_header' => 0]);

        // Update selected ones
        if ($request->has('header_trip_ids')) {
            TripCategory::whereIn('id', $request->header_trip_ids)->update(['is_header' => 1]);
        }

        return response()->json(['status' => true, 'message' => 'Header trips updated successfully.']);
    }


    public function delete(Request $request){
        $tripcategory = TripCategory::find($request->id); // use find(), not findOrFail() to avoid immediate 404
    
        if (!$tripcategory) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Trip category not found.',
            ]);
        }
    
        $tripcategory->delete(); // perform deletion
        return response()->json([
            'status'    => 200,
            'message'   => 'Trip category deleted successfully.',
        ]);
    }
    

    public function sort(Request $request) {
        foreach ($request->order as $item) {
            TripCategory::where('id', $item['id'])->update(['positions' => $item['position']]);
        }

        return response()->json([
            'status'    => 200,
            'message'   => 'Table updated successfully',
        ]);
    }

    //trip category/banner
    public function bannerIndex(Request $request, $trip_cat_id) {
        $trip   = TripCategory::findOrFail($trip_cat_id);
        $banner = TripCategoryBanner::where('trip_cat_id', $trip_cat_id)->paginate(25);
        return view('admin.tripcategory.bannerindex', compact('trip', 'banner'));   
    }

    public function bannerCreate($trip_cat_id) {       
        $trip  = TripCategory::findOrFail($trip_cat_id);
        return view('admin.tripcategory.bannerCreate', compact('trip'));
    }

    public function bannerStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', 
        ]);
    
        $data = $request->all();   
        // Handle Image Upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file       = $request->file('image');
            $extension  = $file->extension(); 
            $fileName   = time() . rand(10000, 99999) . '.' . $extension;           
            // Ensure we store only the relative path in the database
            $filePath   = 'uploads/trip_category_banner/' . $fileName;   
            // Move file only once
            $file->move(public_path('uploads/trip_category_banner'), $fileName);
    
            $data['image'] = $filePath; // Store the relative path in the DB
        }
    
        // Save data using the repository
        $this->TripCategoryRepository->banner_create($data);   
        return redirect()->back()->with('success', 'Trip category Banner created successfully.');    
    }

    public function bannerEdit($banner_id) {
        $tripCategoryBanner = TripCategoryBanner::findOrFail($banner_id);
        return view('admin.tripcategory.banneredit', compact('tripCategoryBanner'));
    }

    public function bannerUpdate(Request $request) {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', 
        ]);
        $data = $request->all();
        $this->TripCategoryRepository->banner_update($data);
        return redirect()->route('admin.tripcategorybanner.list.all', $request->trip_cat_id)->with('success', 'Trip category banner updated successfully.');
    }

    public function bannerStatus($id) {
        $data   = TripCategoryBanner::find($id);
        $update = TripCategoryBanner::where('trip_cat_id', $data->trip_cat_id)->where('id', '!=', $id)->get();
        foreach($update as $k=>$item){
            $item_update = TripCategoryBanner::find($item->id);
            $item_update->status = 0;
            $item_update->save();
        }
        
        $data->status  = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'   => 200,
            'message'  => 'status updated',
        ]);
    }

    public function bannerDelete(Request $request) {

        $tripCategoryBanner = TripCategoryBanner::findOrFail($request->id);
        // If banner is not found then return status 404 with error message
        if (!$tripCategoryBanner) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Banner is not found',
            ]);
        }
        $imagePath = $tripCategoryBanner->image;

        $tripCategoryBanner->delete();
        // If file is exist then remove from target directory
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
        // Return suceess response with message
        return response()->json([
            'status'    => 200,
            'message'   => 'Banner has been deleted successfully',
        ]);
    }


    //trip category/destinations

    public function destinationIndex($trip_cat_id, Request $request){
        $countries  = Country::where('status', 1)->get(); // Get active countries
        $trip       = TripCategory::findOrFail($trip_cat_id);
        $query      = TripCategoryDestination::with('tripdestination')->where('trip_cat_id', $trip_cat_id);

        if($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->whereHas('tripdestination', function($q) use ($keyword) {
                $q->where('destination_name', 'like', '%' . $keyword . '%');
            });
        }

        $tripCategoryDestination = $query->paginate(25);
        return view('admin.tripcategory.destinationIndex', compact('countries', 'trip', 'tripCategoryDestination'));
    }

    public function getDestinationsByCountry($country_id, $trip_cat_id) {

        $assignedDestinationIds = TripCategoryDestination::where('trip_cat_id', $trip_cat_id)->pluck('destination_id')->toArray();
        
        $destinations = Destination::
            select(['id', 'destination_name', 'image'])
            ->where('country_id', $country_id)
            ->where('status', 1)
            ->whereNotIn('id', $assignedDestinationIds)
            ->get();

        if ($destinations->isEmpty()) {
            return response()->json([
                'status'  => 404,
                'message' => 'Destination not found',
            ]);
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Destinations fetched successfully.',
            'destinations' => $destinations,
        ]);
    }
   
    public function destinationDelete(Request $request) {
        $tripdestination = TripCategoryDestination::find($request->id);

        if(!$tripdestination) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Destination not found'
            ]); 
        }

        $tripdestination->delete();
        return response()->json([
            'status'    => 200,
            'message'   => 'Destination deleted succesfully',
        ]);
    }

    public function destinationAdd(Request $request) {

        $request->validate([
            'trip_cat_id'    => 'required|integer',
            'destination_id' => 'required|integer',
            'start_price'    => 'required|numeric|min:0',
        ]);

        $tripCategoryDestination = TripCategoryDestination::create([
            'trip_cat_id'    => $request->trip_cat_id,
            'destination_id' => $request->destination_id,
            'start_price'    => $request->start_price,
            'status'         => 1,
        ]);
        $destination = TripCategoryDestination::with('tripdestination')->where('id', $tripCategoryDestination->id)->first();
        return response()->json([
            'status'  => 201,
            'message' => 'Destination has been added successfully.',
            'destination' => $destination,
        ]);
    }

    public function updatePrice(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:trip_category_destinations,id',
            'start_price' => 'required|numeric|min:0',
        ]);

        $item = TripCategoryDestination::find($request->id);
        $item->start_price = $request->start_price;
        $item->save();

        return redirect()->back()->with('success', 'Price Updated Successfully');
    }

    //trip category activities 
    public function activitiesIndex($trip_cat_id, Request $request)
    {
        $countries  = Country::where('status', 1)->get(); 
        $trip       = TripCategory::findOrFail($trip_cat_id);
        
        $query = TripCategoryActivities::with('tripdestination')->where('trip_cat_id', $trip_cat_id);

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->whereHas('tripdestination', function($q) use ($keyword) {
                $q->where('destination_name', 'like', '%' . $keyword . '%')
                ->orWhere('activity_name', 'like', '%' . $keyword . '%');
            });
        }

        $activities = $query->paginate(25);

        return view('admin.tripcategory.activitiesIndex', compact('countries', 'trip', 'activities'));
    }


    public function getActivitiesByDestination($country_id, $trip_cat_id) {

        $assignedDestinationIds = TripCategoryDestination::where('trip_cat_id', $trip_cat_id)->pluck('destination_id')->toArray();
        
        $destinations = Destination::
            select(['id', 'destination_name', 'crm_destination_id'])
            ->where('country_id', $country_id)
            ->where('status', 1)
            ->whereNotIn('id', $assignedDestinationIds)
            ->get();

        if ($destinations->isEmpty()) {
            return response()->json([
                'status'  => 404,
                'message' => 'Destination not found',
            ]);
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Destinations fetched successfully.',
            'destinations' => $destinations,
        ]);
        
    }

    public function activityAdd(Request $request)
    {
        $request->validate([
            'trip_cat_id'    => 'required|integer',
            'destination_id' => 'required|integer',
            'activity_name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePath = null;
        $logoPath = null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $imageName = time().rand(10000, 99999).'.'.$image->extension();
            $imagePath = 'uploads/trip_activities/'.$imageName;
            $image->move(public_path('uploads/trip_activities'), $imageName);
        }

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logo = $request->file('logo');
            $logoName = time().rand(10000, 99999).'_logo.'.$logo->extension();
            $logoPath = 'uploads/trip_activities/'.$logoName;
            $logo->move(public_path('uploads/trip_activities'), $logoName);
        }

        $tripCategoryActivity = TripCategoryActivities::create([
            'trip_cat_id'    => $request->trip_cat_id,
            'destination_id' => $request->destination_id,
            'activity_name'  => $request->activity_name,
            'image'          => $imagePath,
            'logo'           => $logoPath,
            'status'         => 1,
        ]);

        $activity = TripCategoryActivities::with('tripdestination')->find($tripCategoryActivity->id);

        return response()->json([
            'status'  => 201,
            'message' => 'Activity has been added successfully.',
            'destination' => $activity,
        ]);
    }


    
    public function updateActivities(Request $request)
    {
       
        $request->validate([
            'id' => 'required|exists:trip_category_activities,id',
            'activity_name' => 'required|string|max:255',
            'activity_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'activity_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $activity = TripCategoryActivities::findOrFail($request->id);
        $activity->activity_name = $request->activity_name;

        if ($request->hasFile('activity_image') && $request->file('activity_image')->isValid()) {
            // Delete old image if exists
            if ($activity->image && file_exists(public_path($activity->image))) {
                unlink(public_path($activity->image));
            }

            // Upload new image
            $image = $request->file('activity_image');
            $imageName = time() . rand(10000, 99999) . '.' . $image->extension();
            $imagePath = 'uploads/trip_activities/' . $imageName;
            $image->move(public_path('uploads/trip_activities'), $imageName);
            $activity->image = $imagePath;
        }

        if ($request->hasFile('activity_logo') && $request->file('activity_logo')->isValid()) {
            // Delete old logo if exists
            if ($activity->logo && file_exists(public_path($activity->logo))) {
                unlink(public_path($activity->logo));
            }

            // Upload new logo
            $logo = $request->file('activity_logo');
            $logoName = time() . rand(10000, 99999) . '_logo.' . $logo->extension();
            $logoPath = 'uploads/trip_activities/' . $logoName;
            $logo->move(public_path('uploads/trip_activities'), $logoName);
            $activity->logo = $logoPath;
        }


        $activity->save();
        return redirect()->back()->with('success', 'Activity Updated Successfully');
    }

    public function activitiesStatus(Request $request, $id) {
        $data = TripCategoryActivities::find($id);
        $data->status   = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'status updated',
        ]);
    }


    public function activitiesDelete(Request $request) {
        $tripactivity = TripCategoryActivities::find($request->id);

        if(!$tripactivity) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Activity not found'
            ]); 
        }

        $imagePath = $tripactivity->image;
        $logoPath = $tripactivity->logo;
        $tripactivity->delete();
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }

        if (!empty($logoPath) && file_exists(public_path($logoPath))) {
            unlink(public_path($logoPath));
        }
        return response()->json([
            'status'    => 200,
            'message'   => 'Activity deleted succesfully',
        ]);
    }


}
