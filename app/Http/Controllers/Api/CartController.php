<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function add(Request $request)
    {
        // dd($request->all());
        // dd(Auth::guard('web')->check());

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Something happened'
            ]);
        }

        $token = '';

        // if user is logged in
        if ($request->user_id != 0) {
            // check if product exists in cart
            $productExistsInCart = Cart::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();

            if (!empty($productExistsInCart)) {
                $productExistsInCart->qty = $productExistsInCart->qty + 1;
                $productExistsInCart->save();
            } else {
                $cart = new Cart();
                $cart->user_id = $request->user_id;
                $cart->product_id = $request->product_id;
                $cart->save_for_later = 0;
                $cart->qty = 1;
                $cart->coupon_code = 0;
                $cart->ip = $this->ip;
                $cart->save();

                $cartCount = Cart::where('user_id', $request->user_id)->where('save_for_later', 0)->count();
            }
        } else {
            // check if token exists
            if (!empty($_COOKIE['_cart-token'])) {
                $token = $_COOKIE['_cart-token'];

                // check if product exists in cart
                $productExistsInCart = Cart::where('guest_token', $token)->where('product_id', $request->product_id)->first();

                if (!empty($productExistsInCart)) {
                    $productExistsInCart->qty = $productExistsInCart->qty + 1;
                    $productExistsInCart->save();

                    $cartCount = Cart::where('guest_token', $token)->where('save_for_later', 0)->count();

                    return response()->json([
                        'status' => 200,
                        'token' => '',
                        'count' => $cartCount,
                        'message' => 'Product added to cart'
                    ]);
                }
            } else {
                $token = mt_rand_custom(10);
            }

            $cart = new Cart();
            $cart->user_id = 0;
            $cart->product_id = $request->product_id;
            $cart->save_for_later = 0;
            $cart->qty = 1;
            $cart->ip = $this->ip;
            $cart->guest_token = $token;
            $cart->coupon_code = 0;
            $cart->save();

            $cartCount = Cart::where('guest_token', $token)->where('save_for_later', 0)->count();
        }

        return response()->json([
            'status' => 200,
            'token' => $token,
            'count' => $cartCount,
            'message' => 'Product added to cart'
        ]);
    }
}
