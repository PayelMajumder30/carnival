<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ItenarylistRepositoryInterface;
use App\Models\{ItenaryList, Destination, PackageCategory, DestinationWiseItinerary, TagList};

class ItenaryListController extends Controller
{
    private $ItenarylistRepository;
        public function __construct(ItenarylistRepositoryInterface $ItenarylistRepository){
        $this->ItenarylistRepository = $ItenarylistRepository;
    }


    public function index(Request $request){
        $keyword  = $request->keyword;
        $query    = ItenaryList::with(['itineraryItineraries.destination', 'itineraryItineraries.packageCategory']);

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

        return view('admin.itenaries.list', compact('data', 'destinations', 'packageCategories', 'tags'));
    }

    public function create()
    {
        return view('admin.itenaries.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
        'main_image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
        'title' => 'required|string|max:255|unique:itenary_list,title',
        'short_description' => 'nullable|string|max:255',
        'duration' => 'required|string|max:255',
        'selling_price' => 'required|numeric|lte:actual_price',
        'actual_price' => 'required|numeric',
        'discount_type' => 'required',
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

        if ($request->hasFile('main_image') && $request->file('main_image')->isValid()) {
            $image = $request->file('main_image');
            $imageName = time().rand(10000, 99999).'.'.$image->extension();
            $imagePath = 'uploads/itenaries_list/'.$imageName;
            $image->move(public_path('uploads/itenaries_list'), $imageName);

            $data['main_image'] = $imagePath;
        }


        $this->ItenarylistRepository->create($data);
        return redirect()->route('admin.itenaries.list.all')->with('success', 'New Itenaries created');
    }


    public function edit($id)
    {
        $itenary = $this->ItenarylistRepository->findById($id);
        return view('admin.itenaries.edit', compact('itenary'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,svg|max:5120',
            'title' => 'required|string|max:255|unique:itenary_list,title,' . $id,
            'short_description' => 'nullable|string|max:255',
            'duration' => 'required|string|max:255',
            'selling_price' => 'required|numeric|lte:actual_price',
            'actual_price' => 'required|numeric',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'discount_start_date' => 'required|date',
            'discount_end_date' => 'required|date|after_or_equal:discount_start_date'
        ]);

        $itenary = $this->ItenarylistRepository->findById($id);
        $data = $request->all();

        if($request->hasFile('main_image') && $request->file('main_image')->isValid()) {

            if(!empty($itenary->main_image) && file_exists(public_path($itenary->main_image))){
                unlink(public_path($itenary->main_image));
            }

            $image = $request->file('main_image');
            $imageName = time() . rand(10000, 99999) . '.' . $image->extension();
            $imagePath = 'uploads/itenaries_list/' . $imageName;
            $image->move(public_path('uploads/itenaries_list'), $imageName);

            $data['main_image'] = $imagePath; 
        }
        
        $this->ItenarylistRepository->update($id, $data);

        return redirect()->route('admin.itenaries.list.all')->with('success', 'Itenary updated successfully.');
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

}
