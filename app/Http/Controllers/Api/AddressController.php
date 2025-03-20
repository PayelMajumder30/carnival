<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\UserPincode;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        // $this->middleware('web');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:1',
            'full_name' => 'required|string|min:2|max:255',
            'mobile_number' => 'required|integer|digits:10',
            'email' => 'nullable|email|min:2|max:255',
            'pincode' => 'required|integer|digits:6',
            'locality' => 'required|string|min:2|max:255',
            'city' => 'required|string|min:2|max:255',
            'state' => 'required|string|min:2|max:255',
            'country' => 'nullable|string|min:2|max:255',
            'street_address' => 'required|string|min:2|max:255',
            'landmark' => 'nullable|string|min:2|max:255',
            'type' => 'nullable|string|min:2|max:20',
        ], [
			'mobile_number.*' => 'Enter 10 digits valid mobile number'
		]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ]);
        }

        // check address
        $addressCheck = Address::where('user_id', $request->user_id)->where('mobile_no', $request->mobile_number)->where('pincode', $request->pincode)->where('locality', $request->locality)->first();

        if (!empty($addressCheck)) {
            return response()->json([
                'status' => 400,
                'message' => 'This address is already added',
            ]);
        } else {
            // address store
            $address = new Address();
            // $address->user_id = auth()->guard('web')->check() ? auth()->guard('web')->user()->id : 0;
            $address->user_id = $request->user_id;
            $address->full_name = $request->full_name;
            $address->mobile_no = $request->mobile_number;
            $address->whatsapp_no = $request->whatsapp_no ?? '';
            $address->alt_no = $request->alt_no ?? '';
            $address->email = $request->email ?? '';

            $address->pincode = $request->pincode;
            $address->locality = $request->locality;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->country = $request->country ?? '';
            $address->street_address = $request->street_address;
            $address->landmark = $request->landmark ?? '';
            $address->ip_address = $this->ip;

            // checking for selected/ default address
            $addressExists = Address::where('ip_address', $this->ip)->get();
            if (count($addressExists) > 0) {
                foreach($addressExists as $addressOld) {
                    $addressOld->selected = 0;
                    $addressOld->save();
                }
            }
            $address->selected = 1;
            $address->type = $request->type ?? 'not specified';
            $address->save();

            // adding into user pincode
            $userpinCheck = UserPincode::where('ip_address', $this->ip)->where('locality', $request->locality)->first();

            if (empty($userpinCheck)) {
                $userpin = new UserPincode();
                $userpin->ip_address = $this->ip;
                $userpin->pincode = $request->pincode;
                $userpin->locality = $request->locality;
                $userpin->city = $request->city;
                $userpin->state = $request->state;
                $userpin->country = $request->country;

                // checking for selected/ default pincode
                $userpinExists = UserPincode::where('ip_address', $this->ip)->first();
                if (!empty($userpinExists)) {
                    $userpinExists->selected = 0;
                    $userpinExists->save();
                }
                $userpin->selected = 1;

                $userpin->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Delivery address added',
                'redirect_url' => $request->redirect_url,
            ]);
        }
    }

    public function default(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(),[
            'id' => 'required|integer|min:1|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ]);
        }

        // if (auth()->guard('web')->check()) {
            $addressUpdate = Address::where('ip_address', $this->ip)->update([
                'selected' => 0
            ]);

            // set default
            $address = Address::findOrFail($request->id);
            $address->selected = 1;
            $address->save();

            return response()->json([
                'status' => 200,
                'message' => 'Delivery address changed'
            ]);
        // } else {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'Something happened'
        //     ]);
        // }
    }

    public function detail(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(),[
            'id' => 'required|integer|min:1',
            'user_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ]);
        }

        // find detail
        $address = Address::where('id', $request->id)->where('user_id', $request->user_id)->first();

        if (!empty($address)) {
            return response()->json([
                'status' => 200,
                'message' => 'Address detail found',
                'data' => $address,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Something happened'
            ]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(),[
            'id' => 'required|integer|min:1',
            'user_id' => 'required|integer|min:1',
            'full_name' => 'required|string|min:2|max:255',
            'mobile_number' => 'required|integer|digits:10',
            'email' => 'nullable|email|min:2|max:255',
            'pincode' => 'required|integer|digits:6',
            'locality' => 'required|string|min:2|max:255',
            'city' => 'required|string|min:2|max:255',
            'state' => 'required|string|min:2|max:255',
            'country' => 'nullable|string|min:2|max:255',
            'street_address' => 'required|string|min:2|max:255',
            'landmark' => 'nullable|string|min:2|max:255',
            'type' => 'nullable|string|min:2|max:20',
        ], [
			'mobile_number.*' => 'Enter 10 digits valid mobile number'
		]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ]);
        }

        $address = Address::where('id', $request->id)->where('user_id', $request->user_id)->first();

        if (!empty($address)) {
            $address->full_name = $request->full_name;
            $address->mobile_no = $request->mobile_number;
            $address->whatsapp_no = $request->whatsapp_no ?? '';
            $address->alt_no = $request->alt_no ?? '';
            $address->email = $request->email ?? '';

            $address->pincode = $request->pincode;
            $address->locality = $request->locality;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->country = $request->country ?? '';
            $address->street_address = $request->street_address;
            $address->landmark = $request->landmark ?? '';
            $address->type = $request->type ?? 'not specified';
            $address->save();

            return response()->json([
                'status' => 200,
                'message' => 'Delivery address updated',
                'redirect_url' => $request->redirect_url,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Something happened'
            ]);
        }
    }
}
