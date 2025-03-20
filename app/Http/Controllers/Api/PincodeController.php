<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPincode;

class PincodeController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];;
    }

    public function status(Request $request)
    {
        $userpin = UserPincode::where('ip_address', $this->ip)->latest('id')->get();

        if (count($userpin) > 0) {
            return response()->json([
                'status' => 200,
                'message' => 'User pincode found',
                'data' => $userpin,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'User pincode not found',
            ]);
        }
    }

    public function store(Request $request)
    {
        $userpinCheck = UserPincode::where('ip_address', $this->ip)->where('locality', $request->locality)->first();

        if (!empty($userpinCheck)) {
            return response()->json([
                'status' => 200,
                'message' => 'This address is already added',
            ]);
        } else {
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

            return response()->json([
                'status' => 201,
                'message' => 'New delivery address added',
                'data' => $userpin,
            ]);
        }
    }
}
