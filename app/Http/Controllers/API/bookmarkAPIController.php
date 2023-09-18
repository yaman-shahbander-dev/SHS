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
                    $userLatitude=null;
$userLongitude=null;
                }
                $HiddenColumns = ['custom_fields', 'media', 'has_media', 'pivot'];
                $attrs = $user->vendorFavoriteAPI->makeHidden($HiddenColumns);
                $respone = [];
                $i = 0;
                                  //  return dd($attrs);

                foreach ($attrs as $attr) {
                    $respone[$i]['id'] = $attr->id;
                    $respone[$i]['name'] = $attr->nickname;
                    $respone[$i]['avatar'] = asset('storage/Avatar').'/'.$attr->avatar;
                    $respone[$i]['email'] = $attr->email;
                    $respone[$i]['description'] = $attr->description;
                    $respone[$i]['rating'] = sprintf("%.1f",round((getRating($attr)/20)*2)/2);
                    $respone[$i]['latitude'] = $attr->coordinates!=null? $attr->coordinates->latitude:null;
                    $respone[$i]['longitude'] = $attr->coordinates!=null? $attr->coordinates->longitude:null;

                    $respone[$i]['distance'] = $attr->coordinates!=null ? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)),2) : null;
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

            $vendor=User::find($request->vendor_id);


             //for send notification
        $SERVER_API_KEY = 'AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $data = [
            "registration_ids" => array($vendor->fcm_token),
            "notification" => [
                "title"    => config('notification_lang.Notification_SP_add_favorite_title_' . $vendor->language),
                "body"     =>  $user->name . ' '.config('notification_lang.Notification_SP_add_favorite_body_' . $vendor->language)
            ]
        ];

        $dataString = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
         //return dd(curl_exec($ch));
        $response = curl_exec($ch);
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
