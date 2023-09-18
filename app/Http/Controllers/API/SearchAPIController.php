<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\reviews;

class SearchAPIController extends Controller
{

    public function index(Request $request) {

        if($request->header('devicetoken')) {
            if($request->search==null)
                return $this->sendError('please enter the key word to search', 401);
            $response = [];

            $hiddenElems = ['custom_fields', 'has_media'];
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
                try{
                    $userLatitude = $user->coordinates->latitude;
                    $userLongitude = $user->coordinates->longitude;
                }
                catch (\Exception $e){
                    $userLatitude = null;
                    $userLongitude = null;
                }


                if ($request->search) {

                    $search  = strip_tags($request->search);

                    if ($search == null) {
                        return $this->sendError('Search field can\'t be empty', 401); }


                    $vendors = User::with('roles')
                        ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->where('role_id',3);

                    $vendors = ($vendors)->where("name", "LIKE", "%".$search."%")
                        ->Orwhere("email", "LIKE", "%".$search."%")->where('role_id',3)
                        ->Orwhere("phone", "LIKE", "%".$search."%")->where('role_id',3)
                        ->orwhere("payment_id", "LIKE", "%".$search."%")->where('role_id',3)
                        ->orwhere("description", "LIKE", "%".$search."%")->where('role_id',3)
                        ->orwhere("address", "LIKE", "%".$search."%")->where('role_id',3)
                        ->orwhere("nickname", "LIKE", "%".$search."%")->where('role_id',3)
                        ->orwhere("caption", "LIKE", "%".$search."%")->where('role_id',3)->get();

                }

                $respone = [];

                try{

                    foreach ($vendors as $vendor){

                        $respone[] = [
                            'id' => $vendor->id,
                            'name' => $vendor->nickname,
                            'email' => $vendor->email,
                            'rating' => sprintf("%.1f",(round((getRating($vendor)/20)*2)/2)),
                            'description' => $vendor->description,
                            'latitude' => $vendor->coordinates!=null? $vendor->coordinates->latitude:null,
                            'longitude' => $vendor->coordinates!=null? $vendor->coordinates->longitude:null,

                            'distance' =>$vendor->coordinates!=null && $userLatitude !=null? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($vendor->coordinates->latitude), floatval($vendor->coordinates->longitude)),2) : null,

                            'avatar' => asset('storage/Avatar').'/'.$vendor->avatar
                        ];

                    }

                    usort($respone, function($a, $b) {
                        return $a['distance'] <=> $b['distance'];
                    });


                }

                catch (\Exception $e){

                    return $e->getMessage();}

                return $this->sendResponse($respone, 'vendors retrieved successfully');

            } catch (\Exception $e) {

                return $this->sendError($e->getMessage(), 401);
            }
        } else return $this->sendError('nothing to process', 401);
    }
}
