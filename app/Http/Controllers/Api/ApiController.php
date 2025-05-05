<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{TripCategoryDestination, SocialMedia, Banner, TripCategory, Partner, WhyChooseUs, Setting, Blog};

class ApiController extends Controller
{

    //master module //blog
    public function blogIndex()
    {
        $data = Blog::where('status', 1)->orderBy('id')->get();
        $result = [];
        foreach($data as $key=>$item){
            $result[$key]=[
                'id'               => $item->id,
                'title'            => ucwords($item->title),
                'slug'             => $item->slug,
                'short_desc'       => $item->short_desc,
                'desc'             => $item->desc,
                'meta_type'        => $item->meta_type,
                'meta_description' => $item->meta_description,
                'meta_keywords'    => $item->meta_keywords,
                'image'=>asset($item->image),
            ];
        }
        return response()->json([
            'status'    => true,
            'data'      => $result
        ]);
    }

    public function blogShow($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if(!$blog){
            return response()->json(['status' => false, 'message' => 'Not found']);
        }
        $blog->image = asset($blog->image);
        return response()->json(['status' => true, 'data' => $blog]);
    }

    //master module/ social media
    public function socialmediaIndex() 
    {
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
            'status' => true,
            'data' => $result
        ]);
    }

    public function socialmediaShow($id) 
    {
        $data = SocialMedia::find($id);
        if(!$data) {
            return response()->json(['status'=>false, 'message' => 'Not found']);
        }
        $data->image = asset($data->image);
        return response()->json(['status'=>true, 'data' => $data]);
    }

    
    //master module /partners
    public function partnerIndex()
    {
        $data = Partner::where('status', 1)->orderBy('id')->get();
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
        $whyChooseUs->image = asset($whyChooseUs->image);
        return response()->json(['status' => true, 'data' => $whyChooseUs]);
    }
 
    
   //master module/ trip category
    public function tripIndex()
    {
        $data = TripCategory::with(['tripcategorybanner' => function($query) {
                $query->where('status', 1)->orderBy('id');
            }])->orderBy('positions')->get();

        $result = [];
        foreach($data as $key) {
            $activeBanner = $key->tripcategorybanner->first();
            $result[] = [
                'id'    => $key->id,
                'title' => ucwords($key->title),
                'image' => $activeBanner ? asset($activeBanner->image) : null,
            ];
        }

        return response()->json([
            'status'   => true,
            'data'     => $result
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
            'id'     => $data->id,
            'title'  => ucwords($data->title),
            'image'  => $activeBanner ? asset($activeBanner->image) : null,
        ];

        return response()->json([
            'status'   => true,
            'data'     => $result
        ]);
    }

    
    //master module/ trip category/ destination
    public function getDestinationsByTripCategory($trip_cat_id) {
       $tripCategory = TripCategory::find($trip_cat_id);

        if(!$tripCategory) {
        return response()->json([
            'status'   => false,
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
                'status'   => false,
                'message'   => 'No destinations found for this trip category.',
              
            ]);
        }
        
        return response()->json([
            'status'   => true,
            'message'   => 'Destinations fetched successfully.',
            'data'      => $destinations,
        ]);
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
}   
