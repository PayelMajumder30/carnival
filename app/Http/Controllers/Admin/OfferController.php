<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Offer;

use Illuminate\Http\Request;

class OfferController extends Controller
{
    //
    public function index(){
        $offer = Offer::latest()->paginate(10);
        return view('admin.offers.index', compact('offer'));
    }

    public function create()
    {
        //dd('hi');
        return view('admin.offers.create');
    }
    public function store(Request $request){
        //dd($request->all());
        $request->validate([
           'coupon_code'            => 'required|string|unique:offers,coupon_code|max:255',
           'discount_type'          => 'required|in:flat,percentage',
           'discount_value'         => 'required|numeric|min:0',
           'minimum_order_amount'   => 'required|numeric|min:0',
           'maximum_discount'       => 'required|numeric|min:0',
           'start_date'             => 'required|date|after_or_equal:today',
           'end_date'               => 'required|date|after:start_date',
           'usage_limit'            => 'nullable|integer|min:1',
           'usage_per_user'         => 'nullable|integer|min:1',
        ]);
       
        try{
            DB::beginTransaction();

            $offer = new Offer;
            $offer->coupon_code         = $request->coupon_code;
            $offer->discount_type       = $request->discount_type;
            $offer->discount_value      = $request->discount_value;
            $offer->minimum_order_amount= $request->minimum_order_amount;
            $offer->maximum_discount    = $request->maximum_discount;
            $offer->start_date          = $request->start_date;
            $offer->end_date            = $request->end_date;
            $offer->usage_limit         = $request->usage_limit;
            $offer->usage_per_user      = $request->usage_per_user;
              
            $offer->save();
            // dd($offer);
            DB::commit();

            return redirect()->route('admin.offers.list.all')->with('success', 'Offer created successfully!');
        }
        catch(\Exception $e){
            //dd($e->getMessage());
            DB::rollback();
            \Log::error($e);
            // Redirect back with an error message
            return redirect()->back()->with('failure', 'Failed to create offer. Please try again.');
        }
    }

    public function status(Request $request, $id)
    {
        $data = Offer::find($id);;
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }
}
