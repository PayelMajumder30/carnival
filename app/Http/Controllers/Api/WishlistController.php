<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function toggle(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|min:1',
            'product_id' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Something happened'
            ]);
        }

        // if user is logged in
        if ($request->user_id != 0) {
            // check if product exists in cart
            $productWishlistCheck = Wishlist::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();

            if (!empty($productWishlistCheck)) {
                $productWishlistCheck->delete();
                $type = 'remove';
                $message = 'Product removed from Wishlist';
            } else {
                $wishlist = new Wishlist();
                $wishlist->user_id = $request->user_id;
                $wishlist->product_id = $request->product_id;
                $wishlist->save();

                $type = 'add';
                $message = 'Product added to Wishlist';
            }
        }

        return response()->json([
            'status' => 200,
            'type' => $type,
            'message' => $message
        ]);
    }
}
