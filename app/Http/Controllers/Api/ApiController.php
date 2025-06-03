<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{TripCategoryDestination, SocialMedia, Banner, TripCategory, Partner, 
    WhyChooseUs, Setting, Blog, Offer, PageContent, Destination, TripCategoryActivities, ItenaryList, 
    ItineraryGallery, Support, AboutDestination, DestinationWisePopularPackages, DestinationWiseItinerary,
    DestinationWisePopularPackageTag, PackagesFromTopCities, LeadGenerate};

class ApiController extends Controller
{

    //master module //blog
    public function blogIndex()
    {
        $data = Blog::orderBy('id')->get();
        $result = [];
        foreach($data as $key=>$item){
            $result[$key]=[
            'id' => $item->id,
            'title' =>ucwords($item->title),
            'short_desc' => $item->short_desc,
            'desc' => $item->desc,
            'meta_type' => $item->meta_type,
            'meta_description' => $item->meta_description,
            'meta_keywords' => $item->meta_keywords,
            'image'=>asset($item->image),
            ];
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    public function blogShow($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if(!$blog){
            return response()->json(['status' => false, 'message' => 'Not found']);
        }
        return response()->json(['status' => true, 'data' => $blog]);
    }

     //master module /partners
    public function partnerIndex()
    {
        $data = Partner::orderBy('id')->get();
        $result = [];
        foreach($data as $key=>$item){
           $result[$key] =[
            'id'=>$item->id,
            'title'=>ucwords($item->title),
            'image'=>asset($item->image),
           ];
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }
 
    public function partnerShow($id)
    {
        $partners = Partner::find($id);

        if(!$partners) {
            return response()->json(['status' => false, 'message' => 'Not Found']);
        }
        $partners->image = asset($partners->image);
        return response()->json(['status' => true, 'data' => $partners]);
    }


    //master module/ banner
    public function pageBannerIndex() {
        $data = Banner::orderBy('id')->get();
        return response()->json([
            'status'   => true,
            'data'     => $data
        ]);
    }

    public function pageBannerShow($id) {
        $data = Banner::find($id);       
        if(!$data) {
            return response()->json(['status' => false, 'message' => 'Not found']);
        }
        return response()->json(['status' => true, 'data' => $data]);
    }


    //master module//why-choose-us
    public function whyChooseUsIndex()
    {
        $data = WhyChooseUs::where('status', 1)->orderBy('positions','asc')->get();
        $result = [];
        foreach($data as $key=>$item)
        {
            $result[$key] = [
                'id' => $item->id,
                'title' =>ucwords($item->title),
                'image' =>asset($item->image),
                'desc' =>$item->desc,
                'status' =>$item->status,
                'positions' =>$item->positions,
            ];
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    public function whyChooseUsShow($id)
    {
        $whyChooseUs = WhyChooseUs::find($id);

        if(!$whyChooseUs)
        {
            return response()->json(['status' => false, 'message' => 'Not Found']);
        }
        return response()->json(['status' => true, 'data' => $whyChooseUs]);
    }
 
    
    //master module/ trip category
    public function tripIndex()
    {
        $data = TripCategory::with(['tripcategorybanner' => function($query) {
            $query->where('status', 1)->orderBy('id');
        }])->orderBy('positions')->get();

        $result = [];

        foreach ($data as $index => $data_item) {
            $activeBanner = $data_item->tripcategorybanner->first();
            $result[] = [
                'id'             => $data_item->id,
                'is_highlighted' => $data_item->is_highlighted,
                'title'          => ucwords($data_item->title),
                'short_desc'     => $data_item->short_desc,
                'image'          => $activeBanner ? asset($activeBanner->image) : null,
            ];


            $destinationsData = TripCategoryDestination::with('tripdestination')
                ->where('trip_cat_id', $data_item->id)
                ->where('status', 1)
                ->whereHas('tripdestination', function($q) {
                    $q->where('status', 1);
                })
                ->get();

            if (!empty($destinationsData)) {
                $destinations = [];

                foreach ($destinationsData as $key => $destination) {
                    $tripDest = $destination->tripdestination;

                    // Format destination image and logo
                    $image = $tripDest && $tripDest->image ? asset($tripDest->image) : null;
                    $logo  = $tripDest && $tripDest->logo  ? asset($tripDest->logo) : null;

                    $destinations[$key] = [
                        'id'          => $tripDest ? $tripDest->id : null,
                        'slug'          => $tripDest ? $tripDest->slug : null,
                        'name'        => $tripDest ? $tripDest->destination_name : "N/A",
                        'logo'        => $logo,
                        'image'       => $image,
                        'start_price' => $destination->start_price
                    ];
                }

                $result[$index]['destinations'] = $destinations; 
            }

            // Activities
            // Fetch activities under this destination and trip category
            $activities = TripCategoryActivities::where('trip_cat_id', $data_item->id)
                ->where('status', 1)
                ->get()
                ->map(function($activity) {
                    return [
                        'id'            => $activity->id,
                        'activity_name' => $activity->activity_name,
                        'image'         => $activity->image ? asset($activity->image) : null,
                        'logo'          => $activity->logo ? asset($activity->logo) : null,
                    ];
                });
                $result[$index]['activities'] = $activities;
        }

        return response()->json([
            'status' => true,
            'data'   => $result
        ]);
    }


    public function tripShow($id)
    {
        $data = TripCategory::with(['tripcategorybanner' => function($query) {
            $query->where('status', 1)->orderBy('id');
        }])->find($id);

        if(!$data) {
            return response()->json([
                'status'    => false,
                'message'   => 'Trip category Banner not found'
            ]);
        }
        $activeBanner = $data->tripcategorybanner->first();

        $result = [
            'id'               => $data->id,
            'is_highlighted'   => $data->is_highlighted,
            'title'            => ucwords($data->title),
            'short_desc'       => $data->short_desc,
            'image'            => $activeBanner ? asset($activeBanner->image) : null,
        ];

        return response()->json([
            'status'   => true,
            'data'     => $result
        ]);
    }

    
    //master module/ trip category/ destination
    // public function getDestinationsByTripCategory($trip_cat_id) {
    //    $tripCategory = TripCategory::find($trip_cat_id);

    //     if(!$tripCategory) {
    //     return response()->json([
    //         'status'   => false,
    //         'message'   => 'Trip category not found.',
    //         'data'      => [],
    //     ]);
    //    }

    //     $destinations = TripCategoryDestination::with('tripdestination')
    //         ->where('trip_cat_id', $trip_cat_id)
    //         ->where('status', 1)
    //         ->get();

    //     if($destinations->isEmpty()) {
    //         return response()->json([
    //             'status'   => false,
    //             'message'   => 'No destinations found for this trip category.',
              
    //         ]);
    //     }

    //     $destinations->transform(function ($item) {
    //         if ($item->tripdestination && $item->tripdestination->image) {
    //             $item->tripdestination->image = asset($item->tripdestination->image);
    //         }
    //         return $item;
    //     });
    //     return response()->json([
    //         'status'   => true,
    //         'message'   => 'Destinations fetched successfully.',
    //         'data'      => $destinations,
    //     ]);
    // }


    //master module/ social media
    public function socialmediaIndex() {
        $data = SocialMedia::orderBy('id')->get();
        $result = [];
        foreach($data as $key=>$item)
        {
            $result[$key] = [
                'id' =>$item->id,
                'title' =>ucwords($item->title),
                'image' =>asset($item->image),
                'link' =>$item->link
            ];
        }
        return response()->json([
            'status'   => true,
            'data'      => $result
        ]);
    }

    public function socialmediaShow($id) {
        $data = SocialMedia::find($id);
        if(!$data) {
            return response()->json(['status'=>false, 'message' => 'Not found']);
        }
        $data->image = asset($data->image);
        return response()->json(['status'=>true, 'data' => $data]);
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

     //master module/Offer
    public function offerIndex()
    {
        $data = Offer::where('status', 1)->orderBy('id','asc')->get();
        $result = [];
        foreach($data as $key=>$item)
        {
            $result[$key] = [
                'id'                    =>$item->id,
                'coupon_code'           =>ucwords($item->coupon_code),
                'discount_type'         =>ucwords($item->discount_type),
                'discount_value'        =>number_format($item->discount_value, 0),
                'minimum_order_amount'  =>$item->minimum_order_amount,
                'maximum_discount'      =>$item->maximum_discount,
                'start_date'            =>$item->start_date,
                'end_date'              =>$item->end_date,
                'usage_limit'           =>$item->usage_limit,
                'usage_per_user'        =>$item->usage_per_user,
            ];
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    public function offerShow($id) 
    {
        $data = Offer::find($id);
        if(!$data) {
            return response()->json(['status'=>false, 'message' => 'Not found']);
        }
        $validated = [
            'id'                    =>$data->id,
            'coupon_code'           =>ucwords($data->coupon_code),
            'discount_type'         =>ucwords($data->discount_type),
            'discount_value'        =>number_format($data->discount_value, 0),
            'minimum_order_amount'  =>$data->minimum_order_amount,
            'maximum_discount'      =>$data->maximum_discount,
            'start_date'            =>$data->start_date,
            'end_date'              =>$data->end_date,
            'usage_limit'           =>$data->usage_limit,
            'usage_per_user'        =>$data->usage_per_user,
        ];
        return response()->json(['status'=>true, 'data'=>$validated]);
    }

    //website settings
    public function settingIndex()
    {
        $data = Setting::orderBy('id', 'asc')->get();
        $result = [];
        foreach($data as $key=>$item){
            if($item->id==1){
                $result['official_number'] = $item->content;
            }
            if($item->id==2){
                $result['official_number_alternative'] = $item->content;
            }
            if($item->id==3){
                $result['official_email'] = $item->content;
            }
            if($item->id==11){
                $result['official_email_alternative'] = $item->content;
            }
            if($item->id==4){
                $result['company_name'] = $item->content;
            }
            if($item->id==5){
                $result['company_name_small'] = $item->content;
            }
            if($item->id==6){
                $result['company_description'] = $item->content;
            }
            if($item->id==7){
                $result['company_full_address'] = $item->content;
            }
            if($item->id==8){
                $result['google_map_link'] = $item->content;
            }
            if($item->id==9){
                $result['website_link'] = $item->content;
            }
            if($item->id==10){
                $result['official_whatsapp_number'] = $item->content;
            }
        }
        if(count($result)>0){
            return response()->json([
                'status' => true,
                'data' => $result
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => "Data not found!"
            ]);
        }
    }

    //page content
    public function contentIndex() {
        $data = PageContent::orderBy('id','asc')->get();
        $result = [];
        foreach($data as $key=>$item)
        {
            $result[$key] = [
                'id'        =>$item->id,
                'page'      =>$item->page,
                'title'     =>$item->title,
                'description' =>$item->description       
            ];
        }
        return response()->json([
            'status' => true,
            'data'   => $result
        ]);
    }

    // Itineraries / Itinerary_list
    public function getDestinationPackagesWithItineraries($destination_slug)
    { 
        $destination = Destination::with(['destinationItineraries.packageCategory', 'destinationItineraries.itinerary'])
        ->where('slug', $destination_slug)
        ->where('status', 1)
        ->first();

        if (!$destination) {
            return response()->json([
                'status' => false,
                'message' => 'Destination not found'
            ], 404);
        }

        $result = [
            'id'   => $destination->id,
            'name' => $destination->destination_name,
            'slug' => $destination->slug,
            'banner_image' => asset($destination->banner_image),
            'short_desc' => $destination->short_desc,
        ];

        $groupedPackages = [];

        foreach ($destination->destinationItineraries as $entry) {
            $package = $entry->packageCategory;
            $itinerary = $entry->itinerary;

            if (!$package || !$itinerary) {
                continue; 
            }

            $packageId = $package->id;

            if (!isset($groupedPackages[$packageId])) {
                $groupedPackages[$packageId] = [
                    'package_id'   => $package->id,
                    'package_name' => $package->title,
                    'itineraries'  => []
                ];
            }

            $groupedPackages[$packageId]['itineraries'][] = [
                'itinerary_id'      => $itinerary->id,
                'title'             => $itinerary->title,
                'slug'              => $itinerary->slug,
                'short_description' => $itinerary->short_description,
                'main_image'        => $itinerary->main_image ? asset($itinerary->main_image) : null,
                'duration'          => $itinerary->trip_durations,
                'discount_type'     => $itinerary->discount_type,
                'discount_value'    => $itinerary->discount_value,
                'selling_price'     => $itinerary->selling_price,
                'actual_price'      => $itinerary->actual_price,
            ];
        }

        // Assign grouped package data to result
        $result['packages'] = array_values($groupedPackages);

        //Fetch all active support
        $supports = Support::where('status', 1)->orderBy('id', 'asc')->get(['id', 'title', 'description']);           
        $result['supports'] = $supports;

        return response()->json([
            'status' => true,
            'data'   => $result
        ]);
    }

    // Itineraries / Gallery
    public function itinerariesWithGallery() 
    {
       $data = ItineraryGallery::with('itinerary')->get();
        $result = [];
        foreach($data as $key=>$item)
        {
            // check if related itinerary exists and is active
            if($item->itinerary && $item->itinerary->status == 1) {
                $result[] = [
                    'id' => $item->id,
                    'title' => $item->itinerary->title, // fetch title from related itinerary
                    'image' =>asset($item->image),                   
                ];
            }         
        }
        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    public function itinerariesWithGalleryByid($id)
    {
        $gallery = ItineraryGallery::with(['itinerary' => function($query) {
            $query->where('status', 1);
        }])->find($id);

        if(!$gallery || !$gallery->itinerary) {
            return response()->json(['status' => false, 'message' => 'Image of Galleries not found.']);
        }

        return response()->json([
            'data'=>[
                'title' => $gallery->itinerary->title,
                'image' => asset($gallery->image),
            ]
        ]);
    }

    //Detail page of destination
    
    public function getDestinationDetails($itinerary_slug)
    {
        $existingItinerary = ItenaryList::where('slug',$itinerary_slug)->first();

        if (!$existingItinerary) {
            return response()->json(['message' => 'itinerary not found'], 404);
        }

        $destination = Destination::find($existingItinerary->destination_id);
        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }
       
        $results = [];
        $results['itinerary_id']=$existingItinerary->id;
        $results['title']=$existingItinerary->title;
        $results['trip_durations']=$existingItinerary->trip_durations;
        $results['galleries'] = $existingItinerary->itineraryGallery->map(function ($gallery) {
            return [
                'image_title' => ucwords($gallery->title),
                'image' => asset($gallery->image),
            ];
        })->toArray();
        $results['destination_id'] = $destination->id;
        $results['destination_name'] = $destination->destination_name;
        $results['about_destination'] = optional(optional($destination->aboutDestination)->first())->content ?? null;

        $popularPackages = DestinationWisePopularPackages::with('popularitinerary')
        ->where('destination_id', $destination->id)
        ->where('itinerary_id', '!=', $existingItinerary->id)
        ->where('status', 1)
        ->get()
        ->filter(function ($item) {
            return $item->popularitinerary !== null; // filter out null itineraries
        })
        ->map(function ($item) {
            return [
                'id'            => $item->popularitinerary->id,
                'title'         => $item->popularitinerary->title,
                'nights'        => $item->popularitinerary->total_nights,
                'selling_price' => $item->popularitinerary->selling_price,
                'slug'          => $item->popularitinerary->slug,
            ];
        })
        ->values()->toArray();

        $results['popular_packages'] = $popularPackages;

        $results['packages_from_top_city'] = $destination->packagesFromTopCities->map(function ($toCity) {
            return [
                'id' => $toCity->id,
                'title' => $toCity->title,
                'slug' => $toCity->slug,
            ];
        })->toArray();
        return response()->json($results, 200);
    }
  
    

    //detail page of destination

    // public function getDestinationDetails($destination_id)
    // {
    //     $destination = Destination::find($destination_id);

    //     if (!$destination) {
    //         return response()->json(['message' => 'Destination not found'], 404);
    //     }

    //     // Itineraries mapped via destination_wise_itinerary
    //     $itineraryIds = DestinationWiseItinerary::where('destination_id', $destination_id)
    //         ->distinct()
    //         ->pluck('itinerary_id')
    //         ->toArray();

    //     $itineraries = ItenaryList::whereIn('id', $itineraryIds)->where('status',1)
    //         ->with('itineraryGallery')
    //         ->get();

    //     // About Destination
    //     $about = AboutDestination::where('destination_id', $destination_id)
    //         ->pluck('content')
    //         ->first(); // Just take one

    //     // Popular Itineraries
    //     $popularPackages = DestinationWisePopularPackages::with(['destination', 'popularitinerary'])
    //         ->where('destination_id', $destination_id)
    //         ->get();

    //     // Popular Tags
    //     $popularPackageIds = $popularPackages->pluck('id')->toArray();

    //     $popularTags = DestinationWisePopularPackageTag::with('tag')
    //         ->whereIn('popular_package_id', $popularPackageIds)
    //         ->get()
    //         ->pluck('tag')
    //         ->unique('id') 
    //         ->values();

    //     //packages from top cities
    //     $packagesFromTopCities = PackagesFromTopCities::where('destination_id', $destination_id)->where('status',1)
    //                 ->get()->map(function ($package) {
    //                     return [
    //                         'id'    => $package->id,
    //                         'slug'  => ucwords($package->slug),
    //                     ];
    //                 });
       
    //     $result = [
    //         'destination_id'   => $destination->id,
    //         'destination_name' => $destination->destination_name,

    //         'about' => strip_tags($about),

    //         'itineraries' => $itineraries->map(function ($itinerary) {
    //             return [
    //                 'id'             => $itinerary->id,
    //                 'title'          => $itinerary->title,
    //                 'trip_durations' => $itinerary->trip_durations,
    //                 'selling_price'     => $itinerary->selling_price,
    //                 'actual_price'      => $itinerary->actual_price,
    //                 'gallery'        => $itinerary->itineraryGallery->map(function ($gallery) {
    //                     return asset($gallery->image);
    //                 })->toArray(),
    //             ];
    //         }),

    //         'popular_packages' => $popularPackages->map(function ($item) {
    //             return [
    //                 'destination_name' => $item->destination->destination_name,
    //                 'itinerary_title'  => $item->popularitinerary->title,
    //             ];
    //         }),

    //         'packages_from_top_cities' => $packagesFromTopCities,
    //         //'popular_tags' => $popularTags
    //     ];

    //     return response()->json($result);
    // }
   
    //search by keyword (home page)
    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        if(!$keyword)
        {
            return reponse()->json([
                'message' => 'keyword is required',
                'status' => false,
                'data' => []
            ], 400);
        }

        $destinations = Destination::where('destination_name', 'LIKE', '%' . $keyword . '%')
                        ->select('id','slug','destination_name', 'image', 'short_desc')
                        ->get()
                        ->map(function ($destination) {
                            $destination->destination_name = $destination->destination_name . ' Trips';
                            $destination->image = asset($destination->image); 
                            return $destination;
                        });

        return response()->json([
            'message' => 'Search results',
            'status' => true,
            'data' => $destinations
        ]);
    }

    //for lead generate

    public function leadStore(Request $request)
    {
        //dd($request->all());
        // Map JSON camelCase keys to DB snake_case columns
        $request->merge([
            'destination_id' => $request->json('destinationID'),
            'itinerary_id'   => $request->json('itinaryID'),
            'package_id'     => $request->json('packageID'),
        ]);

        //dd('hi');
        // Extract all expected DB columns from request
        $data = [
            'destination_id'  => $request->destination_id,
            'itinerary_id'    => $request->itinerary_id,
            'package_id'      => $request->package_id,
            'customerName'    => ucwords($request->customerName),
            'travelLocation'  => ucwords($request->travelLocation),
            'travelDuration'  => $request->travelDuration,
            'email'           => $request->email,
            'whatsapp'        => $request->whatsapp,
            'travellers'      => $request->travellers,
            'startDate'       => $request->startDate,
            'endDate'         => $request->endDate,
        ];
        //dd($data);

        // Insert into DB
        $lead = LeadGenerate::create($data);
        //dd($lead);
        return response()->json([
            'status' => true,
            'message' => 'Lead generated successfully!',
            'data' => $lead,
        ], 201);
    }


}
   
