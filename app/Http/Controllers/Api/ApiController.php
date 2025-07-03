<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;



use App\Models\{TripCategoryDestination, SocialMedia, Banner, TripCategory, Partner, 

    WhyChooseUs, Setting, Blog, Offer, PageContent, Destination, TripCategoryActivities, ItenaryList, 

    ItineraryGallery, Support, AboutDestination, DestinationWisePopularPackages, DestinationWiseItinerary,

    DestinationWisePopularPackageTag, PackagesFromTopCities, LeadGenerate, ItineraryDetail, NewsLetter};



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

                'slug'             => $data_item->slug,

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

        $data = Offer::where('status', 1)->orderBy('id', 'asc')->get();

        $result = [];

    

        foreach ($data as $key => $item) {

            $result[$key] = [

                'id'    => $item->id,

                'title' => ucwords($item->title),

                'link'  => $item->link,

                'image' => $item->image ? asset($item->image) : null,

            ];

        }

    

        return response()->json([

            'status' => true,

            'data'   => $result,

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

        $result['about_destination'] = optional(optional($destination->aboutDestination))->content ?? null;



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

                'crm_itinerary_id'  => $itinerary->crm_itinerary_id,

                'valid_days'  => $itinerary->total_days,

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

         

        $result['packages_from_top_city'] = $destination->packagesFromTopCities->map(function ($toCity) {

            return [

                'id' => $toCity->id,

                'title' => $toCity->title,

                'slug' => $toCity->slug,

            ];

        })->toArray();



        return response()->json([

            'status' => true,

            'data'   => $result

        ]);

    }

    public function getDestinationPackagesFromCity($package_from_city_slug)

    { 

        $PackagesFromTopCities = PackagesFromTopCities::where('slug',$package_from_city_slug)->first();

        if(!$PackagesFromTopCities){

             return response()->json([

                'status' => false,

                'mesage'   =>'Data not found'

            ]);

        }

        $destination = Destination::with(['destinationItineraries.packageCategory', 'destinationItineraries.itinerary'])

        ->where('id', $PackagesFromTopCities->destination_id)

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

        $result['about_destination'] = optional(optional($destination->aboutDestination))->content ?? null;



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

                'crm_itinerary_id'  => $itinerary->crm_itinerary_id,

                'valid_days'  => $itinerary->total_days,

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

         

        $result['packages_from_top_city'] = $destination->packagesFromTopCities->map(function ($toCity) {

            return [

                'id' => $toCity->id,

                'title' => $toCity->title,

                'slug' => $toCity->slug,

            ];

        })->toArray();



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

        // dd($existingItinerary);

        if (!$existingItinerary) {

            return response()->json(['message' => 'itinerary not found'], 404);

        }



        $destination = Destination::find($existingItinerary->destination_id);

        if (!$destination) {

            return response()->json(['message' => 'Destination not found'], 404);

        }

       

        $results = [];

        $results['itinerary_id']=$existingItinerary->id;

        $results['price']=$existingItinerary->selling_price;

        $results['crm_itinerary_id']=$existingItinerary->crm_itinerary_id;

        $results['title']=$existingItinerary->title;

        $results['valid_days']=$existingItinerary->total_days;

        $results['trip_durations']=$existingItinerary->trip_durations;

        $results['galleries'] = $existingItinerary->itineraryGallery->map(function ($gallery) {

            return [

                'image_title' => ucwords($gallery->title),

                'image' => asset($gallery->image),

            ];

        })->toArray();

        $results['destination_id'] = $destination->id;

        $results['destination_name'] = $destination->destination_name;

        $results['about_destination'] = optional(optional($destination->aboutDestination))->content ?? null;



       $popularPackages = DestinationWisePopularPackages::with('popularitinerary', 'tags')

        ->where('destination_id', $destination->id)

        ->where('itinerary_id', '!=', $existingItinerary->id)

        ->where('status', 1)

        ->get()

        ->filter(function ($item) {

            return $item->popularitinerary !== null;

        })

        ->map(function ($item) {

            $ch = curl_init(env('CRM_BASEPATH') . 'api/crm/itinerary_inclusion_exclusion/' . $item->popularitinerary->id);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [

                'Content-Type: application/json',

                'Accept: application/json'

            ]);

            $inclusionResponse = curl_exec($ch);

            curl_close($ch);

    

            $inclusionResponseData = json_decode($inclusionResponse, true);

            $inclusion_exclusion = [];

            if (isset($inclusionResponseData['status']) && $inclusionResponseData['status'] === true) {

                $inclusion_exclusion = $inclusionResponseData['data'];

            }

    

            $tags = $item->tags->map(function ($tag) {

                return [

                    'title' => $tag->title,

                ];

            })->toArray();

    

            return [

                "id" => $item->popularitinerary->id,

                "crm_itinerary_id" => $item->popularitinerary->crm_itinerary_id,

                "title" => $item->popularitinerary->title,

                "nights" => $item->popularitinerary->total_nights,

                "selling_price" => $item->popularitinerary->selling_price,

                "slug" => $item->popularitinerary->slug,

                "inclusion_exclusion" => $inclusion_exclusion,

                "tags" => $tags

            ];

        })

        ->values(); // This ensures a clean indexed array format

      

        // divisions_journey (GET request)

        $ch = curl_init(env('CRM_BASEPATH') . 'api/crm/itinerary_divisions/' . $existingItinerary->crm_itinerary_id);

        

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [

            'Content-Type: application/json',

            'Accept: application/json'

        ]);

        

        // Make GET request (default)

        $itineraryResponse = curl_exec($ch);

        curl_close($ch);

        

        $division_summary = json_decode($itineraryResponse, true);

        

        $results['division_summary'] = [];

        // dd($division_summary);

        if (isset($division_summary['status']) && $division_summary['status'] === true) {

            $total_night_count = 0;

            foreach($division_summary['data'] as $summay_key=>$summary_item){

                $results['division_summary'][$summay_key] = $summary_item;

                $trip_summary = [];

                for ($i = 1; $i <= (int)$summary_item['day']; $i++) {

                    $dayNumber = $total_night_count + $i;

                    $headerKey = 'day_' . $dayNumber;

    

                    $activities = ItineraryDetail::where('itinerary_id', $existingItinerary->id)

                            ->where('header', $headerKey)

                            ->where('field', 'day_activity')

                            ->pluck('value');

    

                      $hotelDetails = ItineraryDetail::where('itinerary_id', $existingItinerary->id)

                        ->where('header', $headerKey)

                        ->where('field', 'day_hotel')

                        ->get()

                        ->map(function ($data) {

                            // $data = json_decode($item->value, true); // value contains hotel JSON

                            return [

                                'value'       => $data['value'] ?? '',

                                'image'       => $data['hotel_image'] ?? '',

                                'address'     => $data['hotel_address'] ?? '',

                                'about_hotel' => $data['about_hotel'] ?? '',

                            ];

                        });

    

                    $transfers = ItineraryDetail::where('itinerary_id', $existingItinerary->id)

                        ->where('header', $headerKey)

                        ->where('field', 'day_cab')

                        ->get()

                        ->map(function ($data) {

                            return [

                                'cab_type'      => $data->value ?? '',

                                'location_from' => $data->location_from ?? '',

                                'location_to'   => $data->location_to ?? '',

                            ];

                        });

    

    

                    $trip_summary[] = [

                        'day'   => $dayNumber,

                        'title' => $summary_item['city'],

                        'activities'=> $activities,

                        'hotels'    => $hotelDetails,

                        'transfers' => $transfers,

                    ];

                    $results['division_summary'][$summay_key]['trip_summary'] = $trip_summary;

                }

                    $total_night_count += (int)$summary_item['day']; 

                

            }

        }



        $results['popular_packages'] = $popularPackages;

        // $trip_duration = [];

       $results['trip_duration'] = ItenaryList::where('destination_id', $existingItinerary->destination_id)

        ->orderBy('total_days', 'ASC')

        ->get()

        ->unique('total_days') // Keep only the first item for each unique total_days

        ->map(function ($item) {

            return [

                'id' => $item->id,

                'title' => $item->title,

                'slug' => $item->slug,

                'main_image' => asset($item->main_image),

                'selling_price' => $item->selling_price,

                'destination_id' => $item->destination_id,

                'crm_itinerary_id' => $item->crm_itinerary_id,

                'total_nights' => $item->total_nights,

                'total_days' => $item->total_days,

                'trip_durations' => $item->trip_durations,

            ];

        })

        ->values() // reindex the array

        ->toArray();

        

        $results['packages_from_top_city'] = $destination->packagesFromTopCities->map(function ($toCity) {

            return [

                'id' => $toCity->id,

                'title' => $toCity->title,

                'slug' => $toCity->slug,

            ];

        })->toArray();

        

        $results['best_selling_packages'] = ItenaryList::where('destination_id', $existingItinerary->destination_id)

        // ->orderBy('total_days', 'ASC')

        ->get()// Keep only the first item for each unique total_days

        ->map(function ($item) {

          

            return [

                'id' => $item->id,

                'title' => $item->title,

                'slug' => $item->slug,

                'main_image' => asset($item->main_image),

                'discount_type'     => $item->discount_type,

                'discount_value'    => $item->discount_value,

                'selling_price'     => $item->selling_price,

                'actual_price'      => $item->actual_price,

                'destination_id' => $item->destination_id,

                'crm_itinerary_id' => $item->crm_itinerary_id,

                'total_nights' => $item->total_nights,

                'total_days' => $item->total_days,

                'short_description' => $item->short_description,

                'trip_durations' => $item->trip_durations,

            ];

        })

        ->values() // reindex the array

        ->toArray();



       



        return response()->json($results, 200);

    }

  

    

   

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



    public function trip_category_wise_destination($slug){

        $TripCategory = TripCategory::with('tripcategorydestination')->where('slug', $slug)->first();

        if(!$TripCategory) {

            return response()->json(['status' => 404, 'success' => false, 'message' => 'Trip category Not found']);

        }

        // Get destination IDs from the relationship

        $destinationIds = $TripCategory->tripcategorydestination->pluck('destination_id')->toArray();

        $data = [];

        foreach($destinationIds as $key =>$item){

            $destination =  Destination::find($item);

            if($destination){

               $packages = DestinationWiseItinerary::where('destination_id', $item)

                ->select('itinerary_id')

                ->distinct()

                ->with('itinerary')

                ->get()

                ->map(function ($record) {

                    if ($record->itinerary) {

                        // Add asset() to main_image

                        $itinerary = $record->itinerary;

                        $itinerary->main_image = asset($itinerary->main_image);

                        return $itinerary;

                    }

                    return null;

                })

                ->filter() // Remove nulls if any itinerary relation is missing

                ->values(); 

                $data[$key]=[

                    'destination_id' =>$destination->id,

                    'destination_image' =>asset($destination->image),

                    'destination_name'=>$destination->destination_name,

                    'destination_crm_id' =>$destination->crm_destination_id,

                    'packages' =>$packages,

                ];

            }

        }

        return response()->json(['status' => 200, 'success' => true, 'data' => $data]);

    }


   // subscribe news letter
    public function newsletter(Request $request) {
        // Validate using $request->validate
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:news_letter,email',
        ]);

        $newsletter = NewsLetter::create([
            'email' => $validated['email']
        ]);

        return response()->json([
            'status'    => true,
            'message'   => 'Email subscribed successfully!',
            'data'      => $newsletter
        ], 201);
    }

}

   

