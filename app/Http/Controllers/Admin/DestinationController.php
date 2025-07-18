<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\AboutDestinationInterface;
use App\Interfaces\DestiantionPackageInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\{Country, Destination, ItenaryList, PackageCategory, DestinationWiseItinerary,
                AboutDestination};


class DestinationController extends Controller
{
    
   //construct from repository
    private $aboutDestinationRepository;

    public function __construct(AboutDestinationInterface $aboutDestinationRepository)
    {
        $this->aboutDestinationRepository = $aboutDestinationRepository;
    }
    //
    public function index(Request $request)
    {

        // $Url = env('CRM_BASEPATH').'api/crm/active/country';
        // $ch = curl_init($Url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $countryResponse = curl_exec($ch);
        // curl_close($ch);
        // $countryData = json_decode($countryResponse, true);
        // //dd(env('CRM_BASEPATH'));
        // if ($countryData['status']==true) {
        //     $new_country = $countryData['data'];
        // } else {
        //     $new_country = [];
        // }
       $showCountry = Country::select('country_name')
        ->orderBy('country_name')
        ->pluck('country_name');

       $country_filter = $request->input('country_filter');
        $keyword = $request->input('keyword');

        $data = Destination::with('country') // if you want to eager load related country
            ->when($country_filter, function ($query) use ($country_filter) {
                $query->whereHas('country', function ($q) use ($country_filter) {
                    $q->where('country_name', 'like', '%' . $country_filter . '%');
                });
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('destination_name', 'like', '%' . $keyword . '%');
            })
            ->orderBy('country_id', 'ASC')
            ->orderBy('destination_name', 'ASC')
            ->paginate(25);
        return view('admin.destination.index', compact('data', 'showCountry'));
    }

    public function FetchDataFromCRM(){
        // $Url = env('CRM_BASEPATH').'api/crm/active/country';
        // $ch = curl_init($Url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $countryResponse = curl_exec($ch);
        // curl_close($ch);
        // $countryData = json_decode($countryResponse, true);
        // //dd(env('CRM_BASEPATH'));
        // if ($countryData['status']==true) {
        //     $new_country = $countryData['data'];
        // } else {
        //     $new_country = [];
        // }
        $country_id = 2; //India
        $Url = env('CRM_BASEPATH').'api/crm/active/country/destinations/'.$country_id;
        $ch = curl_init($Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);;

        $countryResponse = curl_exec($ch);
        curl_close($ch);

        $countryData = json_decode($countryResponse, true);
        // dd($countryData);
        if ($countryData['status'] == true) {
            $new_destination = $countryData['data'];
            
            if (count($new_destination) > 0) {
                $existing_country_id = Country::where('crm_country_id',2)->value('id');
                if($existing_country_id){
                    foreach ($new_destination as $key => $item) {
                        // Generate base slug
                        $baseSlug = Str::slug($item['name']);
                        $slug = $baseSlug;
                        $i = 1;

                        // Ensure slug is unique using do...while
                        // do {
                        //     $exists = Destination::where('slug', $slug)->exists();
                        //     if ($exists) {
                        //         $slug = $baseSlug . '-' . $i++;
                        //     }
                        // } while ($exists);
                        Destination::updateOrCreate(
                            ['crm_destination_id' => (int)$item['id']],
                            [
                                'destination_name' => $item['name'],
                                'country_id' => (int)$existing_country_id,
                                'slug' => $slug
                            ],
                        );
                    }
                }
                session()->flash('success', 'Destinations updated successfully.');
            } else {
                session()->flash('failure', 'No destinations found to update.');
            }
        } else {
            session()->flash('failure', 'Failed to fetch country data.');
        }
        return redirect()->route('admin.destination.list.all');

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
            'crm_destination_id'    => 'required|unique:destinations,crm_destination_id',
            'destination_name'      => 'required|string|max:255',
        ]);

        Destination::create([
            'country_id'         => $request->country_id,
            'crm_destination_id' => $validated['crm_destination_id'],
            'destination_name'   => $validated['destination_name'],
            'status' => 1
        ]);

        session()->flash('success', 'Destination added successfully');
        return response()->json(['success'=> true]);
    }

   
    // public function createDestImage(Request $request)
    // {
    //     $request->validate([
    //         'id'             => 'required|exists:destinations,id',
    //         'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    //         'logo'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    //         'banner_type'    => 'required|in:image,video',
    //         'banner_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
    //         'banner_video'   => 'nullable|mimes:mp4,webm|max:20480', // 20MB
    //         'short_desc'     => 'nullable|string|min:1',
    //     ], [
    //         'image.max'            => 'Upload image must not be more than 5MB.',
    //         'logo.max'             => 'Logo must not be more than 5MB.',
    //         'banner_image.max'     => 'Banner image must not be more than 5MB.',
    //         'banner_video.max'     => 'Video must not be more than 20MB.',
    //         'banner_video.mimes'   => 'Only MP4 or WEBM video files are allowed.',
    //     ]);

    //     $destination = Destination::find($request->id);

    //     // Upload main image
    //     if ($request->hasFile('image') && $request->file('image')->isValid()) {
    //         $image = $request->file('image');
    //         $imageName = time() . rand(10000, 99999) . '.' . $image->extension();
    //         $imagePath = 'uploads/country_wise_dest/' . $imageName;
    //         $image->move(public_path('uploads/country_wise_dest'), $imageName);
    //         $destination->image = $imagePath;
    //     }

    //     // Upload logo
    //     if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
    //         $logo = $request->file('logo');
    //         $logoName = time() . rand(10000, 99999) . '_logo.' . $logo->extension();
    //         $logoPath = 'uploads/country_wise_dest/' . $logoName;
    //         $logo->move(public_path('uploads/country_wise_dest'), $logoName);
    //         $destination->logo = $logoPath;
    //     }

    //     // Upload banner media
    //     if ($request->input('banner_type') === 'image') {
    //         if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
    //             $bannerImage = $request->file('banner_image');
    //             $bannerImageName = time() . rand(10000, 99999) . '.' . $bannerImage->extension();
    //             $bannerImagePath = 'uploads/desti_wise_bannerImg/' . $bannerImageName;
    //             $bannerImage->move(public_path('uploads/desti_wise_bannerImg'), $bannerImageName);
    //             $destination->banner_media = $bannerImagePath;
    //         }
    //     } elseif ($request->input('banner_type') === 'video') {
    //         if ($request->hasFile('banner_video') && $request->file('banner_video')->isValid()) {
    //             $bannerVideo = $request->file('banner_video');
    //             $bannerVideoName = time() . rand(10000, 99999) . '.' . $bannerVideo->extension();
    //             $bannerVideoPath = 'uploads/desti_wise_bannerVid/' . $bannerVideoName;
    //             $bannerVideo->move(public_path('uploads/desti_wise_bannerVid'), $bannerVideoName);
    //             $destination->banner_media = $bannerVideoPath;
    //         }
    //     }

    //     if ($request->filled('short_desc')) {
    //         $destination->short_desc = $request->short_desc;
    //     }

    //     $destination->save();

    //     return response()->json(['success' => true]);
    // }

    public function createDestImage(Request $request)
    {
        $request->validate([
            'id'             => 'required|exists:destinations,id',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'banner_type'    => 'required|in:image,video',
            'banner_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'banner_video'   => 'nullable|mimes:mp4,webm|max:20480', // 20MB
            'short_desc'     => 'nullable|string|min:1',
        ], [
            'image.max'            => 'Upload image must not be more than 5MB.',
            'logo.max'             => 'Logo must not be more than 5MB.',
            'banner_image.max'     => 'Banner image must not be more than 5MB.',
            'banner_video.max'     => 'Video must not be more than 20MB.',
            'banner_video.mimes'   => 'Only MP4 or WEBM video files are allowed.',
        ]);

        $destination = Destination::find($request->id);

        // Handle image
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Remove old image if exists
            if (!empty($destination->image) && file_exists(public_path($destination->image))) {
                unlink(public_path($destination->image));
            }

            $image = $request->file('image');
            $imageName = time() . rand(10000, 99999) . '.' . $image->extension();
            $imagePath = 'uploads/country_wise_dest/' . $imageName;
            $image->move(public_path('uploads/country_wise_dest'), $imageName);
            $destination->image = $imagePath;
        }

        // Handle logo
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Remove old logo if exists
            if (!empty($destination->logo) && file_exists(public_path($destination->logo))) {
                unlink(public_path($destination->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . rand(10000, 99999) . '_logo.' . $logo->extension();
            $logoPath = 'uploads/country_wise_dest/' . $logoName;
            $logo->move(public_path('uploads/country_wise_dest'), $logoName);
            $destination->logo = $logoPath;
        }

        // Handle banner media (image or video)
        if ($request->input('banner_type') === 'image') {
            if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
                // Remove old banner if exists
                if (!empty($destination->banner_media) && file_exists(public_path($destination->banner_media))) {
                    unlink(public_path($destination->banner_media));
                }

                $bannerImage = $request->file('banner_image');
                $bannerImageName = time() . rand(10000, 99999) . '.' . $bannerImage->extension();
                $bannerImagePath = 'uploads/desti_wise_bannerImg/' . $bannerImageName;
                $bannerImage->move(public_path('uploads/desti_wise_bannerImg'), $bannerImageName);
                $destination->banner_media = $bannerImagePath;
            }
        } elseif ($request->input('banner_type') === 'video') {
            if ($request->hasFile('banner_video') && $request->file('banner_video')->isValid()) {
                // Remove old banner if exists
                if (!empty($destination->banner_media) && file_exists(public_path($destination->banner_media))) {
                    unlink(public_path($destination->banner_media));
                }

                $bannerVideo = $request->file('banner_video');
                $bannerVideoName = time() . rand(10000, 99999) . '.' . $bannerVideo->extension();
                $bannerVideoPath = 'uploads/desti_wise_bannerVid/' . $bannerVideoName;
                $bannerVideo->move(public_path('uploads/desti_wise_bannerVid'), $bannerVideoName);
                $destination->banner_media = $bannerVideoPath;
            }
        }

        // Handle short description
        if ($request->filled('short_desc')) {
            $destination->short_desc = $request->short_desc;
        }

        $destination->save();

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
        $bannerImagePath = $countryDestination->banner_image;
        // Delete banner from db
        $countryDestination->delete();
        // If file is exist then remove from target directory
        if (!empty($imagePath) && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }

        if (!empty($logoPath) && file_exists(public_path($logoPath))) {
            unlink(public_path($logoPath));
        }

         if (!empty($bannerImagePath) && file_exists(public_path($bannerImagePath))) {
            unlink(public_path($bannerImagePath));
        }
        // Return suceess response with message
        return response()->json([
            'status'    => 200,
            'message'   => 'Countrywise destination has been deleted successfully',
        ]);
    }

    public function destinationItineraryIndex(Request $request, $destination_id) {

        // Get the keyword from the request
        $keyword = $request->input('keyword');

        // Assigned combinations
        $assignedCombination = DestinationWiseItinerary::where('destination_id', $destination_id)
            ->get(['package_id', 'itinerary_id']);

        $assignedItineraryIds = $assignedCombination->pluck('itinerary_id')->toArray();

        // Base query with relationships
        $packages = DestinationWiseItinerary::where('destination_id', $destination_id)
            ->where(function ($q) use ($keyword) {
                $q->whereHas('packageCategory', function ($q2) use ($keyword) {
                    $q2->where('title', 'like', '%' . $keyword . '%');
                })->orWhereHas('itinerary', function ($q3) use ($keyword) {
                    $q3->where('title', 'like', '%' . $keyword . '%');
                });
            })
            ->get()
            ->toArray();

        $data = [];
        
        foreach ($packages as $index=> $item) {

            $packageTitle = PackageCategory::find($item['package_id'])->title ?? null;
            //$itineraryTitle = ItenaryList::find($item['itinerary_id'])->title ?? null;
            $itinerary = ItenaryList::find($item['itinerary_id']);


            if ($packageTitle) {
                // Group itineraries under each package title
                //$data[$packageTitle]['itineraries'][$item['id']] = $itineraryTitle;
                $data[$packageTitle]['itineraries'][$item['id']] = $itinerary;
            }
        }
        // Get available packages and itineraries
        $packageCategories = PackageCategory::select(['id', 'title'])->where('status', 1)->get();
        $itineraries = ItenaryList::select(['id', 'title'])->where('status', 1)->get();
        $destination = Destination::select(['id', 'destination_name'])->where('id', $destination_id)->first();

        return view('admin.destination.itineraryList', compact(
            'destination',
            'data',
            'packageCategories',
            'itineraries'
        ));
    }

    // public function assignItineraryToDestination(Request $request) {
    //     $is_exist = DestinationWiseItinerary::where([
    //         'destination_id' => $request->destination_id,
    //         'package_id' => $request->package_id,
    //         'itinerary_id' => $request->itinerary_id,
    //     ])->first();
    //     // If same itinerary of same package is aleady added then return error
    //     if (!empty($is_exist)) {
    //         return redirect()->route('admin.destination.itineraryList', $request->destination_id)->with('failure', 'Selected destination of same package category is already added');
    //     }

    //     DestinationWiseItinerary::create([
    //         'destination_id' => $request->destination_id,
    //         'package_id' => $request->package_id,
    //         'itinerary_id' => $request->itinerary_id,
    //         'status' => 1,
    //     ]);
    //     return redirect()->route('admin.destination.itineraryList', $request->destination_id)->with('success', 'Itinerary is assigned successfully with the destination');
    // }

    public function assignItineraryToDestination(Request $request)
    {
        // Validate input
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'package_id' => 'required|exists:package_categories,id',
            'itinerary_id' => 'required|array',
            'itinerary_id.*' => 'exists:itenary_list,id',
        ]);

        $alreadyExists = [];

        foreach ($request->itinerary_id as $itineraryId) {
            // Check if the combination already exists
            $is_exist = DestinationWiseItinerary::where([
                'destination_id' => $request->destination_id,
                'package_id' => $request->package_id,
                'itinerary_id' => $itineraryId,
            ])->first();

            if ($is_exist) {
                $alreadyExists[] = $itineraryId;
                continue;
            }

            // Create new combination
            DestinationWiseItinerary::create([
                'destination_id' => $request->destination_id,
                'package_id' => $request->package_id,
                'itinerary_id' => $itineraryId,
                'status' => 1,
            ]);
        }

        if (!empty($alreadyExists)) {
            return redirect()
                ->route('admin.destination.itineraryList', $request->destination_id)
                ->with('failure', 'Some itineraries were already assigned and skipped.');
        }

        return redirect()
            ->route('admin.destination.itineraryList', $request->destination_id)
            ->with('success', 'Itineraries assigned successfully to the destination.');
    }


    public function deleteItinerary(Request $request) {
        $data = DestinationWiseItinerary::find($request->id); // use find(), not findOrFail() to avoid immediate 404    
        if (!$data) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Destinationwise Itinerary not found.',
            ]);
        }
    
        $data->delete(); // perform deletion
        return response()->json([
            'status'    => 200,
            'message'   => 'Destinationwise Itinerary deleted successfully.',
        ]);
    }


 

    //master modules/destionation/about destination Index
    public function aboutDestiIndex(Request $request, $destination_id) {

        $keyword = $request->keyword ?? '';
        $destination = Destination::findOrFail($destination_id);

        $aboutDestination = AboutDestination::where('destination_id', $destination_id)->first();

        return view('admin.destination.aboutDestinationIndex', compact('destination', 'aboutDestination')); 
    }

    //master modules/destionation/create content for about destination
    public function aboutDestiCreate($destination_id) {       
        $destination  = Destination::findOrFail($destination_id);
        return view('admin.destination.aboutDestinationCreate', compact('destination'));
    }

    //master modules/destionation/store content for about destination 
    public function aboutDestiStore(Request $request)
    {
        $request->validate([
            'content' => 'required|unique:about_destinations,content', 
            'destination_id' => 'required|exists:destinations,id',
        ]);
    
        // Call repository to store content
        $data = [
            'destination_id' => $request->input('destination_id'),
            'content' => $request->input('content'),
        ];
        $this->aboutDestinationRepository->create($data);

        return redirect()->route('admin.destination.aboutDestination.list', $request->destination_id)
                            ->with('success', 'About Destination content created successfully');    
    }

    //master modules/destionation/edit content for about destination 
    public function aboutDestiEdit($id)
    {
        $data = $this->aboutDestinationRepository->findById($id);
        $destination = Destination::findOrFail($data->destination_id);
        return view('admin.destination.aboutDestinationEdit', compact('data', 'destination'));
    }

    //master modules/destionation/store content for about destination 
    public function aboutDestiUpdate(Request $request)
    {
        $id = $request->input('id');
        $request->validate([
           'content' => 'required|unique:about_destinations,content,'. $id,
        ]);
        
        $this->aboutDestinationRepository->update($id, ['content'=> $request->get('content')]);
    
        return redirect()->route('admin.destination.aboutDestination.list', ['destination_id' => $request->destination_id])
                    ->with('success', 'About Destination content updated successfully.');
    }

    //master modules/destionation/delete content for about destination
    public function aboutDestiDelete(Request $request){
        $content = AboutDestination::find($request->id); 
    
        if (!$content) {
            return response()->json([
                'status'    => 404,
                'message'   => 'Content not found.',
            ]);
        } 
        $content->delete(); 
        return response()->json([
            'status'    => 200,
            'message'   => 'Content deleted successfully.',
        ]);
    }
 
}

    

