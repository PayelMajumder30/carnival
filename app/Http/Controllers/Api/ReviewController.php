<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReviewInteract;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }

    public function check(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1',
            'user_id' => 'required|integer|min:1',
            'type' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Something happened'
            ]);
        }

        // if user is logged in
        if ($request->user_id != 0) {
            // check
            $reviewInteractionCheck = ReviewInteract::where('review_id', $request->id)->where('user_id', $request->user_id)->first();

            if (!empty($reviewInteractionCheck)) {

                if ($reviewInteractionCheck->type == $request->type) {
                    $reviewInteractionCheck->delete();
                    $type = 'remove';
                } else {
                    $reviewInteractionCheck->type = $request->type;
                    $reviewInteractionCheck->save();
                    $type = 'add';
                }
            } else {
                $reviewIntrct = new ReviewInteract();
                $reviewIntrct->review_id = $request->id;
                $reviewIntrct->user_id = $request->user_id;
                $reviewIntrct->type = $request->type;
                $reviewIntrct->save();

                $type = 'add';
            }
        }

        return response()->json([
            'status' => 200,
            'type' => $type,
            'message' => 'Thanks for your feedback'
        ]);
    }
}
