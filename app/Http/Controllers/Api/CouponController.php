<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];;
    }

    public function status(Request $request)
    {
        $couponChk = Coupon::where('coupon_code', $request->coupon)->first();

        if (!empty($couponChk)) {

            // coupon/ voucher
            $is_coupon = ($couponChk->is_coupon == 1) ? 'Coupon' : 'Voucher';

            // check code status & expiry date
            if ($couponChk->end_date <= date('Y-m-d') || $couponChk->status == 0) {
                return response()->json([
                    'status' => 400,
                    'message' => $is_coupon.' expired',
                ]);
            }

            // check coupon usage
            if (Auth::guard('web')->user()) {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $couponChk->id)
                ->where('user_id', Auth::guard('web')->user()->id)
                ->count();
            } else {
                $couponUsageCount = CouponUsage::where('coupon_code_id', $couponChk->id)->where('ip', $this->ip)->count();
            }

            if (($couponUsageCount == $couponChk->max_usage_single_user) || 
            ($couponUsageCount > $couponChk->max_usage_single_user)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'You cannot use this '.$is_coupon.' anymore',
                ]);
            }

            // if coupon is applied
            $cartData = Cart::where('ip', $this->ip)->update(['coupon_code' => $couponChk->id]);
            $data = [
                'coupon' => $couponChk->coupon_code,
                'type' => $is_coupon,
                'coupon_type' => $couponChk->type,
                'coupon_amount' => $couponChk->amount,
                'coupon_details' => ($couponChk->type == 1) ? $couponChk->amount.'% OFF' : '&#8377;'.number_format($couponChk->amount).' OFF'
            ];

            return response()->json([
                'status' => 200,
                'message' => $is_coupon.' applied',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid Coupon',
            ]);
        }
    }

    public function remove(Request $request)
    {
        $cartData = Cart::where('ip', $this->ip)->update(['coupon_code' => 0]);

        if ($cartData) {
            return response()->json([
                'status' => 200,
                'message' => 'Coupon removed'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Something happened'
            ]);
        }
    }
}
