<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class bookmarkAPIController extends Controller
{
    public function index(Request $request) {
        if($request->header('devicetoken')) {
            $response = [];

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
                    //   return $this->sendError(, 401);

                }
                $HiddenColumns = ['custom_fields', 'media', 'has_media', 'pivot'];
                $attrs = $user->vendorFavoriteAPI->makeHidden($HiddenColumns);
                $respone = [];
                $i = 0;
                foreach ($attrs as $attr) {
                    $respone[$i]['id'] = $attr->id;
                    $respone[$i]['name'] = $attr->name;
                    $respone[$i]['avatar'] = asset('storage/Avatar').'/'.$attr->avatar;
                    $respone[$i]['last_name'] = $attr->last_name;
                    $respone[$i]['description'] = $attr->description;
                    $respone[$i]['rating'] = sprintf("%.1f",round((getRating($attr)/20)*2)/2);
                    $respone[$i]['latitude'] = $attr->coordinates!=null? $attr->coordinates->latitude:'No coordinates provided for the current vendor';
                    $respone[$i]['longitude'] = $attr->coordinates!=null? $attr->coordinates->longitude:'No coordinates provided for the current vendor';

                    $respone[$i]['distance'] = $attr->coordinates!=null ? distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)) : 'No coordinates provided for the current vendor';
                    $i++;
                }
                return $this->sendResponse($respone, 'favorites retrieved successfully');
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), 401);
            }
        }
        else
            return $this->sendError('nothing to process', 401);

    }

    public function store(Request $request) { // Add to favorite
        if($request->header('devicetoken')) {
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
                if (!$user->vendorFavoriteAPI->contains($request->vendor_id)) {
                    $user->vendorFavoriteAPI()->attach($request->vendor_id);
                    return $this->sendResponse([], 'The user added succesfully');
                } else {
                    return $this->sendResponse([], 'The user is already in favorite list');
                }

            } catch (\Exception $e) {
                    return $this->sendError($e->getMessage(), 401);
            }
        }
    }

    public function remove(Request $request) { // remove from favorite list
        if($request->header('devicetoken')) {
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
                if ($user->vendorFavoriteAPI->contains($request->vendor_id)) {
                    $user->vendorFavoriteAPI()->detach($request->vendor_id);
                    return $this->sendResponse([], 'The user removed succesfully');
                } else {
                    return $this->sendResponse([], 'The user is not in favorite list');
                }

            } catch (\Exception $e) {
                    return $this->sendError($e->getMessage(), 401);
            }
        }
    }
}
