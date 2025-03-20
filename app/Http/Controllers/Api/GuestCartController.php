<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cart;
use App\Models\Product;

class GuestCartController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function add(Request $request)
    {
        $request->validate([
            'user_id' => 'required|in:0',
            'product_id' => 'required|integer|min:1'
        ]);

        /*
        if (isset($_COOKIE['cartProducts'])) {

            $cartProductsObj = json_decode($_COOKIE['cartProducts'], true);

            foreach($cartProductsObj as $product) {

                // if same product
                if ($product['id'] == $request->product_id) {
                    dd('same');
                } else {
                    // dd('diff');

                    $data[] = [
                        'id' => $request->product_id,
                        'qty' => 1,
                        'created_on' => date('Y-m-d H:i:s'),
                        'updated_on' => date('Y-m-d H:i:s')
                    ];
                }
            }

            array_push($cartProductsObj, $data);

            setcookie('cartProducts', json_encode($data), time() + (86400 * 60));

            dd($_COOKIE);

        } else {
            $data[] = [
                'id' => $request->product_id,
                'qty' => 1,
                'created_on' => date('Y-m-d H:i:s'),
                'updated_on' => date('Y-m-d H:i:s')
            ];

            setcookie('cartProducts', json_encode($data), time() + (86400 * 60));

            // $_COOKIE['cartProducts'];
        }
        */

        // unset($_COOKIE);

        if (isset($_COOKIE["cartProductsNew"])) {
            $cookie_data = stripslashes($_COOKIE['cartProductsNew']);
    
            $cart_data = json_decode($cookie_data, true);
        } else {
            $cart_data = array();
        }
    
        $item_id_list = array_column($cart_data, 'item_id');

        // dd($cart_data);
    
        if (in_array($request->product_id, $item_id_list)) {
            foreach ($cart_data as $keys => $values) {
                if ($cart_data[$keys]["item_id"] == $request->product_id) {
                    $cart_data[$keys]["item_quantity"] = $cart_data[$keys]["item_quantity"] + 1;
                }
            }
        } else {
            $item_array = array(
                // 'item_id' => $_POST["hidden_id"],
                'item_id' => $request->product_id,
                'item_quantity' => 1,
            );
            $cart_data[] = $item_array;
        }
    
        $item_data = json_encode($cart_data);
        setcookie('cartProductsNew', $item_data, time() + (86400 * 30));

        return response()->json([
            'status' => 200,
            'message' => 'Product added to cart'
        ]);
    }
}
