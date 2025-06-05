<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Interfaces\ItenarylistRepositoryInterface;
use App\Models\{ItenaryList, Destination, PackageCategory, DestinationWiseItinerary, TagList, ItineraryGallery,ItineraryDetail};

class ItenaryListController extends Controller
{
    private $ItenarylistRepository;
        public function __construct(ItenarylistRepositoryInterface $ItenarylistRepository){
        $this->ItenarylistRepository = $ItenarylistRepository;
    }


    public function index(Request $request){
        $keyword  = $request->keyword;
        $query    = ItenaryList::with(['itineraryItineraries.destination', 'itineraryItineraries.packageCategory','tags']);

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                ->orWhere('short_description', 'like', '%'.$keyword.'%');
            });
        }
        $data = $query->latest('id')->paginate(25);

        
        $tags = TagList::where('status', 1)->get();

      
        $destinations = Destination::select('id', 'destination_name')->get(); //for fetching destination_name from destination table
        $packageCategories = PackageCategory::select('id', 'title')->get(); //for fetching title from package_categories table

        return view('admin.itineraries.list', compact('data', 'destinations', 'packageCategories', 'tags'));
    }

    public function ItineraryBuilder($id,$tab){
        $active_tab = $tab;
        $itinerary = ItenaryList::find($id);
        if(!$itinerary){
            abort(404);
        }

        $postData = [
            'division_ids' => explode(',', $itinerary->stay_by_division_journey) 
        ];

        // dd($postData);
        $ch = curl_init(env('CRM_BASEPATH').'api/crm/active/divisions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData)); // encode only once!
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $itineraryResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

       $itineraryData = json_decode($itineraryResponse, true); // decode JSON to array

       $DayDivisons =[];

        $trip_highlight = ItineraryDetail::where('itinerary_id',$id)->where('header','summarised')->where('field','trip_highlight')->get();
        $activities = ItineraryDetail::where('itinerary_id',$id)->where('field','day_activity')->get();
        $total_cabs = ItineraryDetail::where('itinerary_id',$id)->where('field','day_cab')->get();

        if (isset($itineraryData['status']) && $itineraryData['status'] === true) {
            $DayDivisons = $itineraryData['data'];
        }

         return view('admin.itineraries.itinerary_builder', compact('itinerary','active_tab','DayDivisons','trip_highlight','activities', 'total_cabs'));
    }

    public function FetchCabs($division_id){
        $url = env('CRM_BASEPATH').'api/crm/active/division-wise/cabs/'.$division_id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $itineraryResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseCab = json_decode($itineraryResponse, true);
        if ($httpCode === 200 && isset($responseCab['status']) && $responseCab['status'] === true) {
            return response()->json([
                'success' => true,
                'message' => 'cab fetched successfully.',
                'data' => $responseCab['data']
            ], 200);
        }
       // dd($responseCab);
        return response()->json([
            'success' => false,
            'message' => $responseCab['message'] ?? 'Failed to fetch cabs.',
            'data' => []
        ], 400);     
    }


    public function storeCab(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'itinerary_id' => 'required|integer',
                'cab_id' => 'required|integer',
                'cab_name' => 'required|string',
                'location_from' => 'required|string',
                'location_to' => 'required|string'
            ]);

            $itineraryId = $request->input('itinerary_id');
            $dayNumber = $request->input('day_number');
            $cabName = $request->input('cab_name');

            ItineraryDetail::updateOrCreate(
                [
                'itinerary_id'    => $itineraryId,
                'header'          => 'day_' . $dayNumber,
                'field'           => 'day_cab',
                'location_from'   => ucwords($request->input('location_from')),
                'location_to'     => ucwords($request->input('location_to')),
                ],
                [
                'value'           => $cabName,
                'images'          => null,
                ]
            );

            DB::commit();

            $cabDetails = ItineraryDetail::where('itinerary_id', $itineraryId)
                ->where('header', 'day_' . $dayNumber)
                ->where('field', 'day_cab')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'value' => $item->value,
                        'location_from' => $item->location_from,
                        'location_to' => $item->location_to,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Cab saved successfully.',
                'data' => $cabDetails
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to save cab.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function SaveHighlight(Request $request)
    {
        $highlight = ItineraryDetail::updateOrCreate(
            [
                'itinerary_id' => $request->itinerary_id,
                'header' => 'summarised',
                'field' => 'trip_highlight',
                'value' => $request->highlight, // This uniquely identifies the row
            ],
            [
                'images' => null, // If you want to store/update additional columns
            ]
        );

        return response()->json([
            'status' => true,
            'highlight_id' => $highlight->id,
            'message' => 'Highlight saved successfully.'
        ]);

    }
    public function ActivityCreate(Request $request){
        DB::beginTransaction();

        try {
            $itineraryId = $request->input('itinerary_id');
            $dayNumber = $request->input('day_number');
            $activityNames = $request->input('activity_name');

            foreach ($activityNames as $activityName) {
                ItineraryDetail::updateOrCreate(
                    [
                        'itinerary_id' => $itineraryId,
                        'header' => 'day_'.$dayNumber,
                        'field' => 'day_activity',
                        'value' => $activityName,
                    ],
                    [
                        'images' => null,
                    ]
                );
            }
            DB::commit();
            $getAllData = ItineraryDetail::where('itinerary_id',$itineraryId)->where('header','day_'.$dayNumber)->where('field','day_activity')->get();
            return response()->json([
                'status' => true,
                'data'=>$getAllData,
                'message' => 'Activities saved successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Failed to save activities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function ActivityDelete(Request $request){
        $delete = ItineraryDetail::find($request->id);
        if($delete){
            $delete->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Activity deleted successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Activity not ound'
            ],400);
        }
    }
    public function DeleteHighlight(Request $request){
        $delete = ItineraryDetail::find($request->id);
        if($delete){
            $delete->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Trip highlight deleted successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Trip highlight not ound'
            ],400);
        }
    }

    public function create()
    {
        $destinations = Destination::where('status', 1)
                        ->orderby('destination_name','ASC')
                        ->get();
        return view('admin.itineraries.create', compact('destinations'));
    }

    public function get_itineraries_from_crm(Request $request){

         $url = env('CRM_BASEPATH').'api/crm/active/destinations/'.$request->destination_id.'/itinerary';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $itineraryResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $itineraryData = json_decode($itineraryResponse, true);
            if ($httpCode === 200 && isset($itineraryData['status']) && $itineraryData['status'] === true) {
                return response()->json([
                    'success' => true,
                    'message' => 'Itineraries fetched successfully.',
                    'data' => $itineraryData['data']
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => $itineraryData['message'] ?? 'Failed to fetch itineraries.',
                'data' => []
            ], 400);
    }
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
        'main_image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
        'title' => 'required|string|max:255|unique:itenary_list,title',
        'short_description' => 'nullable|string|max:255',
        'trip_durations' => 'required|string|max:255',
        'selling_price' => 'required|numeric|lte:actual_price',
        'actual_price' => 'required|numeric',
        'destination_id' => 'required|exists:destinations,id',
        'crm_itinerary_id' => 'required|string|max:255',
        'stay_by_division_journey' => 'nullable|string|max:255',
        'total_nights' => 'required|integer|min:1',
        'total_days' => 'required|integer|min:1',
        'discount_type' => 'nullable|in:percentage,flat',
        'discount_value' => 'required',
        'discount_start_date' => 'required|date',
        'discount_end_date' => 'required|date|after_or_equal:discount_start_date',
        ],[
        'title.required' => 'The title is required.',
        'title.string' => 'The title must be a valid string.',
        'title.max' => 'The title cannot exceed 255 characters.',
        'title.unique' => 'This title already exists. Please choose a different one.',
        'main_image.max' => 'The image should not be more than 5mb.',
        'short_description.max' => 'The description may not be greater than 255 characters.',
        'selling_price.lte' => 'Selling price must be equal to or less than the actual price.',
        ]);

        $data = $request->all();

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while(ItenaryList::where('slug', $slug)->exists()){
            $slug = $originalSlug . '_' . $counter;
            $counter++;
        }

        $data['slug'] = $slug;

        if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
            $image = $request->file('main_image');
            $imageName = time().rand(10000, 99999).'.'.$image->extension();
            $imagePath = 'uploads/itineraries_list/'.$imageName;
            $image->move(public_path('uploads/itineraries_list'), $imageName);

            $data['main_image'] = $imagePath;
        }
        
        $this->ItenarylistRepository->create($data);
        return redirect()->route('admin.itineraries.list.all')->with('success', 'New itineraries created');
    }


    public function edit($id)
    {
        $itenary = $this->ItenarylistRepository->findById($id);
        $destinations = Destination::where('status', 1)
                        ->orderby('destination_name','ASC')
                        ->get();
        return view('admin.itineraries.edit', compact('itenary', 'destinations'));
    }

    public function update(Request $request, $id)
    {

        //dd($request->all());
        $request->validate([
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
            'title' => 'required|string|max:255|unique:itenary_list,title,' . $id,
            'short_description' => 'nullable|string|max:255',
            'trip_durations' => 'required|string|max:255',
            'selling_price' => 'required|numeric|lte:actual_price',
            'actual_price' => 'required|numeric',
            'destination_id' => 'required|exists:destinations,id',
            'crm_itinerary_id' => 'required|string|max:255',
            'stay_by_division_journey' => 'nullable|string|max:255',
            'total_nights' => 'required|integer|min:1',
            'total_days' => 'required|integer|min:1',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required',
            'discount_start_date' => 'required|date',
            'discount_end_date' => 'required|date|after_or_equal:discount_start_date'
        ]);

        $itenary = $this->ItenarylistRepository->findById($id);
        $data = $request->all();

        if ($request->title !== $itenary->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $counter = 1;

            while (ItenaryList::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $data['slug'] = $slug;
        } else {
            $data['slug'] = $itenary->slug; 
        }

        if($request->hasFile('main_image') && $request->file('main_image')->isValid()) {

            if(!empty($itenary->main_image) && file_exists(public_path($itenary->main_image))){
                unlink(public_path($itenary->main_image));
            }

            $image = $request->file('main_image');
            $imageName = time() . rand(10000, 99999) . '.' . $image->extension();
            $imagePath = 'uploads/itineraries_list/' . $imageName;
            $image->move(public_path('uploads/itineraries_list'), $imageName);

            $data['main_image'] = $imagePath; 
        }
        
        $this->ItenarylistRepository->update($id, $data);

        return redirect()->route('admin.itineraries.list.all')->with('success', 'Itenary updated successfully.');
    }

    public function toggleStatus($id)
    {
        $data = ItenaryList::find($id);
        $data->status   = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'status updated',
        ]);
    }

    public function delete($id)
    {
        $itenary = $this->ItenarylistRepository->findById($id);
        if (!$itenary) {
            return response()->json(['status' => 404, 'message' => 'Itinerary not found']);
        }

        if(!empty($itenary->main_image) && file_exists(public_path($itenary->main_image))){
            unlink(public_path($itenary->main_image));
        }

        $this->ItenarylistRepository->delete($id);

        return response()->json(['status' => 'success', 'message' => 'Itenary Deleted Successfully']);
    }


    
    //for assigned the destination and packages under itinerary
    public function assignedItinerary(Request $request)
    {
        try{
            DB::beginTransaction();
            $request->validate([
                'itinerary_id' => 'required|exists:itenary_list,id',
                'destination_id' => 'required|exists:destinations,id',
                'package_id' => 'required|array',
                'package_id.*' => 'exists:package_categories,id',
            ]);
            $itineraryId = $request->itinerary_id;
            $destinationId = $request->destination_id;
                foreach ($request->package_id as $packageId) {
                    DestinationWiseItinerary::updateOrCreate(
                    [
                        'destination_id' => (int)$destinationId,
                        'package_id' => (int)$packageId,
                        'itinerary_id' => (int)$itineraryId,
                    ],
                    [
                        'status' => 1,
                    ]
                );
            }
            DB::commit();

            return redirect()->back()->with('success', 'Destination and Packages assigned successfully.');
        }catch(\Exception $e){
            dd($e->getMessage());
            DB::rollback();
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create offer. Please try again.');
        }
    }

    //for check uncheck the destinationwise package categories
    public function togglePackageStatus(Request $request)
    {
        $request->validate([
            'itinerary_id'   => 'required|exists:itenary_list,id',
            'destination_id' => 'required|exists:destinations,id',
            'package_id'     => 'required|exists:package_categories,id',
            'status'         => 'required|in:0,1',
        ]);

        //dd($request->all());
        $row = DestinationWiseItinerary::where('itinerary_id', $request->itinerary_id)
                ->where('destination_id', $request->destination_id)
                ->where('package_id', $request->package_id)
                ->first();

        if($row) {
            $row->status = $request->status;
            $row->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    //for delete itinerarywise package
    public function packageItineraryDelete(Request $request)
    {
        $pckg = DestinationWiseItinerary::find($request->id); 

        if (!$pckg) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Itinerarywise package not found.',
            ]);
        }

        // Store related values before deletion
        $itineraryId    = $pckg->itinerary_id;
        $destinationId  = $pckg->destination_id;

        $pckg->delete();

        // Check if any more packages remain for this destination in the same itinerary
        $remaining = DestinationWiseItinerary::where('itinerary_id', $itineraryId)
            ->where('destination_id', $destinationId)
            ->count();
        return response()->json([
            'status'             => 200,
            'message'            => 'Itinerarywise package deleted successfully.',
            'remove_destination' => !$remaining,
            'destination_id'     => $destinationId,
        ]);
    }

    public function assignTagToItenary(Request $request)
    {
        $tagId = $request->tag_id;
        $itenaryId = $request->itenary_id;

        $exists = DB::table('itineraries_tags')
            ->where('tag_id', $tagId)
            ->where('itenary_id', $itenaryId)
            ->exists();

        if ($exists) {
            DB::table('itineraries_tags')
                ->where('tag_id', $tagId)
                ->where('itenary_id', $itenaryId)
                ->delete();

            return response()->json(['status' => 'detached']);
        } else {
            DB::table('itineraries_tags')->insert([
                'tag_id' => $tagId,
                'itenary_id' => $itenaryId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'attached']);
        }
    }


    //itineraries/gallery
    public function galleryIndex(Request $request, $itinerary_id) {
        $itinerary = ItenaryList::findOrFail($itinerary_id);
        $gallery   = ItineraryGallery::where('itinerary_id', $itinerary_id)->paginate(25);
        return view('admin.itineraries.itineraryGalleryIndex', compact('itinerary', 'gallery'));   
    }

    //itineraries/create gallery
    public function aboutDestiCreate($itinerary_id) {       
        $itinerary  = ItenaryList::findOrFail($itinerary_id);
        return view('admin.itineraries.itineraryGalleryIndex', compact('itinerary'));
    }

    //itineraries/store gallery

    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('itinerary_galleries', 'title'),
            ],
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
            'itinerary_id' => 'required|exists:itenary_list,id',
        ], [
            'title.required'    => 'The title field is required.',
            'title.string'      => 'The title must be a valid string.',
            'title.max'         => 'The title cannot exceed 255 characters.',
            'title.unique'      => 'This title already exists. Please use a different one.',

            'image.required'    => 'Please upload an image.',
            'image.image'       => 'The uploaded file must be an image.',
            'image.mimes'       => 'The image must be a type of: jpg, jpeg, png, webp, gif, or svg.',
            'image.max'         => 'The image must not be larger than 5MB.',

            'itinerary_id.required' => 'Itinerary ID is required.',
            'itinerary_id.exists'   => 'The selected itinerary does not exist.',
        ]);

        $data = [];
        $data['title'] = $request->input('title');
        $data['itinerary_id'] = $request->input('itinerary_id');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file       = $request->file('image');
            $extension  = $file->extension();
            $fileName   = time() . rand(10000, 99999) . '.' . $extension;
            $filePath   = 'uploads/itinerary_galleries/' . $fileName;

            $file->move(public_path('uploads/itinerary_galleries/'), $fileName);
            $data['image'] = $filePath;
        }

        \Log::info('Gallery data:', $data);
        $this->ItenarylistRepository->gallery_create($data);

        return redirect()->back()->with('success', 'Gallery created successfully.');
    }



    //itineraries/edit gallery
    public function galleryEdit($id) {
        $itineraryGallery = ItineraryGallery::findOrFail($id);
        return view('admin.itineraries.galleryEdit', compact('itineraryGallery'));
    }

    public function galleryUpdate(Request $request) {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('itinerary_galleries', 'title')->ignore($request->id),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);
        $data = $request->all();
        $this->ItenarylistRepository->gallery_update($data);
        return redirect()->route('admin.itineraries.galleries.list', $request->itinerary_id)->with('success', 'Gallery updated successfully.');
    }

    //itineraries/delete gallery
    public function galleryDelete(Request $request) 
    {
        $itineraryGallery = ItineraryGallery::findOrFail($request->id);
        
        if (!$itineraryGallery) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Gallery is not found',
            ]);
        }
        $imagePath = $itineraryGallery->image;

        $itineraryGallery->delete();
        // If file is exist then remove from target directory
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
        // Return suceess response with message
        return response()->json([
            'status'    => 200,
            'message'   => 'Gallery has been deleted successfully',
        ]);
    }

    public function cabDelete(Request $request)
    {
        try {
            $cab = ItineraryDetail::findOrFail($request->id);
            $cab->delete();

            return response()->json([
                'status' => true,
                'message' => 'Cab deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete cab.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
