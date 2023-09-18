<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\reviews;
use App\Models\User;
use Validator;

class ReviewsAPIController extends Controller
{
    public function leaveReview(Request $request)
    {
        try {
        if ($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();
            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $input = $request->all();

            $input['approved'] = 0;

            $rules = [
                'price_rating' => 'required',
                'service_rating' => 'required',
                'speed_rating' => 'required',
                'trust_rating' => 'required',
                'knowledge_rating' => 'required',
                'vendor_id' => 'required',
                'description' => 'required'
            ];

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                $response = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());

                return $this->sendResponse($response, 'Error');

            } else {
                $input['client_id'] = $user->id;

                $input['price_rating']     = $input['price_rating'] * 20;
                $input['service_rating']   = $input['service_rating'] * 20;
                $input['speed_rating']     = $input['speed_rating'] * 20;
                $input['trust_rating']     = $input['trust_rating'] * 20;
                $input['knowledge_rating'] = $input['knowledge_rating'] * 20;

                reviews::create($input);
                $input['price_rating']     =round(($input['price_rating'] /20)*2)/2 ;
                $input['service_rating']   = round(( $input['service_rating']/20)*2)/2 ;
                $input['speed_rating']     = round(($input['speed_rating'] /20)*2)/2 ;
                $input['trust_rating']     = round(($input['trust_rating']/20)*2)/2 ;
                $input['knowledge_rating'] = round(($input['knowledge_rating'] /20)*2)/2;

                return $this->sendResponse($input, 'Review Added successfully');

            }
        }
      } catch (\Exception $e) {
        return $this->sendError('something was wrong', 401);
     }
    }
}
