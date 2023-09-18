<?php

namespace App\Http\Controllers\API;

use App\Models\GmapLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Homeowner_filter;
use App\Models\Gallery;
use App\Balance;
use App\subCategory;
use App\Models\Fee;
use App\Models\Category;
use App\Models\Day;
use App\Models\reviews;
use App\vendors_suggested;
use DB;
use Illuminate\Support\Facades\Validator;

class vendorApiController extends Controller
{


    public function index(Request $request)
    {

        if ($request->header('devicetoken')) {
            $response = [];
            $hiddenElems = ['custom_fields', 'has_media'];
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
                try {
                    $userLatitude = $user->coordinates->latitude;
                    $userLongitude = $user->coordinates->longitude;
                } catch (\Exception $e) {
                    $userLatitude = null;
                    $userLongitude = null;
                }
                $id = $request->id; //subcategory id

                $userID = $user->id; // user id
                $userSetting = null;
                $userSetting = Homeowner_filter::where('homeOwner_id', $userID)->first(); // user setting


                $respone = [];
                //
                $maxBalance = 0;

                $maxBalanceId = 0;


                try {
                    $Allvendors = subCategory::find($id)->vendors;
                } catch (\Exception $e) {
                    return $this->sendError('there is no vendor for this subcategory', 401);
                }


                try {


                    if ($userSetting->vendor_offer == 1) { // Vendors with special offers first

                        $Allvendors = User::whereHas('specialOffers', function ($query) use ($id) {
                            $query->where('subcategory_id', $id);
                        })->get();
                    }

                    if ($userSetting->vendor_working == 1) { // Vendors who currently not working are filtered out first

                        $datetime = $request->dateTime;

                        $time_input = strtotime($datetime);

                        $date_input = getDate($time_input);

                        $Allvendors = User::with('days')->whereHas('days', function ($query) use ($date_input) {

                            $query->where('days.id', ($date_input['wday'] + 1))->where('days_vendors.start', '<', ($date_input['hours'] . ':' . $date_input['minutes'] . ':' . $date_input['seconds']))->where('days_vendors.end', '>=', ($date_input['hours'] . ':' . $date_input['minutes'] . ':' . $date_input['seconds']));
                        })->get();
                    }

                    $Allvendors = $Allvendors->where('city_id', $user->city_id);
                    foreach ($Allvendors as $vendor) {

                        if ($vendor->Balance != null) {


                            if ($vendor->Balance->balance > $maxBalance) {
                                $maxBalance = $vendor->Balance->balance;

                                $maxBalanceId = $vendor->Balance->id;
                            }
                        }

                        $respone['vendor_list'][] = [
                            'id' => $vendor->id,
                            'name' => $vendor->nickname,
                            'email' => $vendor->email,
                            'rating' => sprintf("%.1f", (round((getRating($vendor) / 20) * 2) / 2)),
                            'description' => $vendor->description,
                            'latitude' => $vendor->coordinates != null ? $vendor->coordinates->latitude : null,
                            'longitude' => $vendor->coordinates != null ? $vendor->coordinates->longitude : null,

                            'distance' => $vendor->coordinates != null && $userLatitude != null ? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($vendor->coordinates->latitude), floatval($vendor->coordinates->longitude)), 2) : null,

                            'avatar' => asset('storage/Avatar') . '/' . $vendor->avatar
                        ];
                    }

                    if ($maxBalanceId != 0) {

                        $featuredVendor = User::where('balance_id', $maxBalanceId)->get();
                    } else {


                        $featuredVendor = null;
                    }
                    //  return $featuredVendor;
                    //                    $respone[$i]['distance'] = $attr->coordinates!=null ? distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)) : 'No coordinates provided for the current vendor';

                    if ($maxBalanceId != 0) {
                        $respone['featuredVendor'] =

                            [
                                'id' => $featuredVendor[0]->id,
                                'name' => $featuredVendor[0]->nickname,
                                'email' => $featuredVendor[0]->email,
                                'latitude' => $featuredVendor[0]->coordinates != null ? $featuredVendor[0]->coordinates->latitude : null,
                                'longitude' => $featuredVendor[0]->coordinates != null ? $featuredVendor[0]->coordinates->longitude : null,
                                'rating' => sprintf("%.1f", round((getRating($featuredVendor[0]) / 20) * 2, 2) / 2),
                                'description' => $featuredVendor[0]->description,
                                'distance' => $featuredVendor[0]->coordinates != null && $userLatitude != null ? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($featuredVendor[0]->coordinates->latitude), floatval($featuredVendor[0]->coordinates->longitude))) : null,
                                'avatar' => asset('storage/Avatar') . '/' . $featuredVendor[0]->avatar
                            ];
                    }
                }
                //                asset('storage/Avatar').'/'.$attr->avatar;
                catch (\Exception $e) {
                    return $e->getMessage();
                }

                //                $featuredVendor = User::;
                //                $featuredVendor = $Allvendors->transform(function($q){
                //                    $balance=0;
                //                    if($q->Balance->balance > $balance)
                //                        $balance=$q->Balance->balance;
                ////                    $q->orderBy('balances.balance');
                //                    return $balance;
                //                });
                //return $featuredVendor

                //for rating
                // usort($respone['vendor_list'], function($a, $b) {
                //     return $b['rating'] <=> $a['rating'];
                // });



                // return $this->sendResponse($respone, 'vendors retrieved successfully');

                try {
                    if ($userSetting->vendor_filter == 1) { // Vendors rating first

                        //    for rating
                        usort($respone['vendor_list'], function ($a, $b) {
                            return $b['rating'] <=> $a['rating'];
                        });
                    } else { // nearest distance first

                        //for distance
                        usort($respone['vendor_list'], function ($a, $b) {
                            return $a['distance'] <=> $b['distance'];
                        });
                    }
                } catch (\Exception $e) {
                }




                return $this->sendResponse($respone, 'vendors retrieved successfully');
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), 401);
            }
        } else
            return $this->sendError('nothing to process', 401);
    }

    public function vendorFeefunc(Request $request)
    {
        if ($request->header('devicetoken')) {
            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $featuredVendor = User::find($request->vendor_id);

            $featuredVendorBalance = $featuredVendor->Balance->balance;

            $fee = Fee::first(); // if there is a fee value

            if (!empty($fee)) {
                $featuredVendorBalance -= $fee->fee_amount;
            }
            $featuredVendor->Balance->balance = $featuredVendorBalance;
            $featuredVendor->Balance->save();

            return $this->sendResponse([], 'Fee subtracted successfully');
        }
    }

    public function profile(Request $request)
    { // for homeOwners
        if ($request->header('devicetoken')) {
            $response = [];
            $hiddenElems = ['custom_fields', 'has_media'];
            $isFavorite = false;
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();

                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $vendor = User::find($request->vendor_id);

                if (!$user->vendorViews->contains($request->vendor_id)) {
                    $user->vendorViews()->attach($request->vendor_id);

                    $SERVER_API_KEY = 'AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq';

                    $headers = [
                        'Authorization: key=' . $SERVER_API_KEY,
                        'Content-Type: application/json',
                    ];

                    $data = [
                        "registration_ids" => array($vendor->fcm_token),
                        "notification" => [
                            "title"    => config('notification_lang.Notification_SP_open_profile_title_' . $vendor->language),
                            "body"     =>  $user->name . ' ' . config('notification_lang.Notification_SP_open_profile_body_' . $vendor->language)
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
                }


                $respone = [];
                $reviewsHiddenColumns = ['custom_fields', 'media', 'has_media'];
                $attrs = $vendor->clientsAPI->makeHidden($reviewsHiddenColumns);
                $reviews = [];
                $i = 0;



                $checkIfFavorite = $user->vendorFavorite->transform(function ($q) use ($vendor) {
                    // if ($vendor->id == $q->id)
                    return $q->id;
                });

                //return $checkIfFavorite ;
                in_array($vendor->id, $checkIfFavorite->toArray()) ? $isFavorite = true : $isFavorite = false;

                foreach ($attrs as $attr) {
                    $reviews[$i]['id'] = $attr->id;
                    $reviews[$i]['name'] = $attr->name;
                    $reviews[$i]['avatar'] = asset('storage/Avatar') . '/' . $attr->avatar;
                    $reviews[$i]['last_name'] = $attr->last_name;
                    $reviews[$i]['rating'] = sprintf("%.1f", round((getFullRating(reviews::find($attr->pivot->id)) / 20) * 2) / 2);
                    $reviews[$i]['description'] = $attr->pivot->description;
                    $i++;
                }

                $respone = [
                    'id'             => $vendor->id,
                    'name'           => $vendor->name,
                    'email'          => $vendor->email,
                    'Owner_name'    => $vendor->owner_name,
                    'caption'          => $vendor->caption,
                    'city'          =>$vendor->cities==null?' ':$vendor->cities->city_name,
                    'Business_name' => $vendor->nickname,
                    'device_token'   => $vendor->device_token,
                    'rating'         => sprintf("%.1f", round((getRating($vendor) / 20) * 2) / 2),
                    'description'    => $vendor->description,
                    'phone'          => $vendor->phone,
                    'latitude' => $vendor->coordinates == null ? null : $vendor->coordinates->latitude,
                    'longitude' => $vendor->coordinates == null ? null : $vendor->coordinates->longitude,
                    'avatar'         => asset('storage/Avatar') . '/' . $vendor->avatar,
                    'background'         => asset('storage/vendors_background') . '/' . $vendor->background_profile,
                    'address'        => $vendor->address,
                    'website'        => $vendor->website,
                    'subcategories'  => $vendor->subcategoriesApi->makeHidden('pivot')->transform(function($q){
                        $q['image']=asset('storage/subcategoriesPic').'/'.($q->image==null?'image_default.png':$q->image);
                    return $q;
                    }),
                    'offers'         => $vendor->specialOffers->makeHidden(['user_id', 'created_at', 'updated_at']),
                    'reviews_count'  => count($reviews),
                    'reviews'        => $reviews,
                    'working_hours'  => $vendor->daysApi->transform(function ($q) {
                        $q['check'] = 1;

                        $q['name'] = $q['name_en'];
                        $q['workHours'] = [
                            'start' => date("g:i A", strtotime($q->start)),
                            'end' => date("g:i A", strtotime($q->end))
                        ];
                        return $q->only('id', 'name', 'check', 'workHours');
                    }),
                    'availability'   => $vendor->vendor_city->makeHidden('pivot'),
                    'gallery'        => $vendor->gallery->transform(function($gallery){
                        $gallery['image'] = asset('storage/gallery') . '/' . $gallery['image'];
                        return $gallery->only('image','id');
                    }),
                    'is_favorite'     =>  $isFavorite
                ];
                return $this->sendResponse($respone, 'vendor profile retrieved successfully');
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), 401);
            }
        } else
            return $this->sendError('nothing to process', 401);
    }



    public function categorySubCatFunc(Request $request)
    {

        $lang = false;
        if (!empty($request->header('devicetoken'))) {
            $vendor = User::where('device_token', $request->header('devicetoken'))->first();
            $l = $vendor->language;
            $arr = [
                'lang' => $vendor->language,
                'bool' => $lang
            ];
            if (!empty($vendor)) {
                $hiddenElems = ['created_at', 'updated_at', 'custom_fields', 'has_media'];
                try {
                    $categories  = Category::with('subCategory')->get(['id', 'name_' . $vendor->language, 'description'])->makeHidden($hiddenElems);
                    $lang = true;
                } catch (\Exception  $e) {
                    $categories  = Category::with('subCategory')->get()->makeHidden($hiddenElems);
                    $lang = false;
                }

                $respone = [];
                foreach ($categories as $category) {
                    $respone[] = [
                        'id'    => $category->id,
                        'name'  => $lang ?
                            $category['name_' . $vendor->language] : $category['name'],
                        'description'    => $category->description,
                        'sub_categories' => $category->subCategoryAPI->transform(function ($q) use ($arr) {

                            return $arr['bool'] ? $q->only(['id', 'name_' . $arr['lang']]) : $q->only(['id', 'name']);
                        })
                    ];
                }

                return $this->sendResponse($respone, 'Categories with subcategories retrieved successfully!');
            } else {
                return $this->sendResponse([], 'User not found!');
            }
        } else {
            return $this->sendResponse([], 'Error!');
        }
    }



    public function workHours(Request $request)
    {
        if (!empty($request->header('devicetoken'))) {
            $vendor = User::where('device_token', $request->header('devicetoken'))->first();
            if (!empty($vendor)) {
                try {
                    $Days  = Day::all(['id', 'name_' . $vendor->language]);
                } catch (\Exception  $e) {
                    $Days  = Day::all(['id', 'name_en']);
                }

                $respone[] = $Days;
                return $this->sendResponse($respone, 'days retrieved successfully!');
            } else {
                return $this->sendResponse([], 'User not found!');
            }
        } else {
            return $this->sendResponse([], 'Error!');
        }
    }

    public function vendorReviews(Request $request)
    {
        if ($request->header('devicetoken')) {
            $response = [];
            $hiddenElems = ['custom_fields', 'has_media'];
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
                $response = $user->clientsAPI->transform(function ($q) {

                    $r = reviews::find($q->pivot->id);
                    return $q = [
                        'user_id' => $q->id,
                        'name' => $q->name,
                        'description' => $q->pivot->description,
                        'avatar' => asset('storage/Avatar') . '/' . $q->avatar,
                        'reviews' => getFullRating($r)
                    ];
                });

                return $this->sendResponse($response, 'Reviews retrieved successfully!');
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), 401);
            }
        }
    }

    public function backgroundAvatar(Request $request)
    {
        if ($request->header('devicetoken')) {
            $response = [];
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $response = [
                    'avatar'    => asset('storage/Avatar') . '/' . $user->avatar,
                    'background' => asset('storage/vendors_background') . '/' . $user->background_profile
                ];

                return $this->sendResponse($response, 'Data retrieved successfully!');
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage(), 401);
            }
        }
    }

    public function vendorInfo(Request $request)
    {
        try {
            if ($request->header('devicetoken')) {
                $response = [];
                try {
                    $user = User::where('device_token', $request->header('devicetoken'))->first();
                    if (empty($user)) {
                        return $this->sendError('User not found', 401);
                    }
                    $response = [
                        'caption' => $user->caption,
                        'Business_name' => $user->nickname,
                        'Owner_name' => $user->owner_name,
                        'About_you' => $user->description
                    ];

                    return $this->sendResponse($response, 'Data retrieved successfully!');
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage(), 401);
                }
            }
        } catch (\Exception $e) {
            return $this->sendError('something was wrong', 401);
        }
    }

    public function vendorInfoUpdate(Request $request)
    {
        try {
            if ($request->header('devicetoken')) {
                $response = [];
                try {
                    $user = User::where('device_token', $request->header('devicetoken'))->first();
                    if (empty($user)) {
                        return $this->sendError('User not found', 401);
                    }

                    $user->name = $request->ownerName;
                    $user->caption = $request->input('caption') == null ? '' : $request->input('caption', '');
                    $user->description = $request->input('aboutyou') == null ? '' : $request->input('aboutyou', '');
                    $user->nickname = $request->input('businessName') == null ? '' : $request->input('businessName', '');
                    if ($user->save()) {

                        $response = [
                            'caption' => $user->caption,
                            'Business_name' => $user->nickname,
                            'Owner_name' => $user->owner_name,
                            'About_you' => $user->description
                        ];

                        return $this->sendResponse($response, 'Data retrieved successfully!');
                    } else
                        return $this->sendError('error save User information', 401);
                } catch (\Exception $e) {
                    return $this->sendError('something was wrong!', 401);
                }
            } else
                return $this->sendError('Error!', 401);
        } catch (\Exception $e) {
            return $this->sendError('something was wrong!', 401);
        }
    }


    public function contactLocation(Request $request)
    {
        if ($request->header('devicetoken')) {
            $response = [];
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
                $response = [
                    'coordinates'        => [
                        'latitude'  => $user->coordinates == null ? null : $user->coordinates->latitude,
                        'longitude' => $user->coordinates == null ? null : $user->coordinates->longitude
                    ],
                    'address'        => $user->address,
                    'website'        => $user->website,
                    'phone_number'   => $user->phone
                ];

                return $this->sendResponse($response, 'Data retrieved successfully!');
            } catch (\Exception $e) {
                return $this->sendError('something was wrong', 401);
            }
        } else
            return $this->sendError('Error!', 401);
    }

    public function contactLocationUpdate(Request $request)
    {
        try {
            if ($request->header('devicetoken')) {
                $response = [];
                try {
                    $user = User::where('device_token', $request->header('devicetoken'))->first();
                    if (empty($user)) {
                        return $this->sendError('User not found', 401);
                    }
                    if ($user->coordinates == null) {
                        $coordinates = new GmapLocation();
                        $coordinates->user_id = $user->id;
                        $coordinates->save();
                    }
                    $user->coordinates->latitude = $request->input('latitude') == null ? null : $request->input('latitude');
                    // $user->coordinates->latitude  = $request->latitude;
                    // $user->coordinates->longitude = $request->longitude;
                    $user->coordinates->longitude = $request->input('longitude') == null ? null : $request->input('longitude');

                    $user->address = $request->input('address') == null ? '' : $request->input('address', '');

                    $user->website = $request->input('website') == null ? '' : $request->input('website', '');
                    $user->phone = $request->input('phone') == null ? '' : $request->input('phone', '');


                    $user->save();
                    $user->coordinates->save();

                    $response = [
                        'coordinates' =>
                        [

                            'latitude' => $user->coordinates == null ? null : $user->coordinates->latitude,
                            'longitude' => $user->coordinates == null ? null : $user->coordinates->longitude

                        ],
                        'address' => $user->address,
                        'website' => $user->website,
                        'phone' => $user->phone
                    ];

                    return $this->sendResponse($response, 'Data retrieved successfully!');
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage(), 401);
                }
            } else
                return $this->sendError('Error!', 401);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }
    }

    public function supportedSubcategpries(Request $request)
    {
        try {
            $lang = false;
            if ($request->header('devicetoken')) {
                $vendor = User::where('device_token', $request->header('devicetoken'))->first();

                //return $sub;

                if (!empty($vendor)) {

                    $sub = $vendor->subcategories->transform(function ($q) {

                        return $q->id;
                    });
                    $l = $vendor->language;
                    $arr = [
                        'lang' => $vendor->language,
                        'bool' => $lang
                    ];
                    $ids = [3, 4, 7];
                    $hiddenElems = ['created_at', 'updated_at', 'custom_fields', 'has_media'];
                    //                   try {
                    //                       $categories = Category::with('subCategory')->get(['id', 'name_' . $vendor->language, 'description'])->transform(function ($q) use ($sub) {
                    //
                    //                           $q->subCategory->transform(function ($q) use ($sub) {
                    //                               if (in_array($q->id, $sub->toArray()))
                    //                                   $q['check'] = 1;
                    //                               else
                    //                                   $q['check'] = 0;
                    //                               return $q;
                    //                           });
                    //                           return $q;
                    //                       });
                    //                       $lang = true;
                    //                   } catch (\Exception  $e) {
                    //                       return $this->sendError('something was wrong ');
                    //                   }

                    $respone = [];
                    //                   foreach ($categories as $category) {
                    //                       $respone[] = [
                    //                           'id' => $category->id,
                    //                           'name' => $lang ?
                    //                               $category['name_' . $vendor->language] : $category['name'],
                    //                           'description' => $category->description,
                    //                           'sub_categories' => $category->subCategory->transform(function ($q) use ($arr) {
                    //
                    //                               return $q->only(['id', 'name', 'check']);
                    //                           })
                    //                       ];
                    //
                    //                   }

                    return $this->sendResponse($sub, 'Categories with subcategories retrieved successfully!');
                } else {
                    return $this->sendResponse([], 'User not found!');
                }
            } else {
                return $this->sendResponse([], 'Error!');
            }
        } catch (\Exception  $e) {
            return $this->sendError('something was wrong ');
        }
    }
    public function saveSupportedSubcategpries(Request $request)
    {
        if ($request->header('devicetoken')) {
            $vendor = User::where('device_token', $request->header('devicetoken'))->first();
            if (!empty($vendor)) {

                $vendor->subcategories()->sync($request->subcategories);
                return $this->sendResponse([], 'Categories and Subcategories saved successfully');
            } else {
                return $this->sendResponse([], 'User not found');
            }
        } else {
            return $this->sendResponse([], 'Error');
        }
    }


    public function vendorRefer(Request $request)
    {
        if ($request->header('devicetoken')) {
            $hiddenElems = ['custom_fields', 'has_media', 'media'];

            $response = [];

            $referer = User::where('device_token', $request->header('devicetoken'))->first('id')->makeHidden($hiddenElems);

            if (empty($referer)) {
                return $this->sendError('User not found', 401);
            }

            $rules = [
                'name' => 'required',
                'phone' => 'required'
            ];

            $response = [
                'name'    =>  $request->name,
                'email'   =>  $request->email,
                'phone'   =>  $request->phone,
            ];

            $validator = Validator::make($response, $rules);

            if ($validator->fails()) {

                return $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
            } else {

                vendors_suggested::create([
                    'name'    =>  $request->name,
                    'email'   =>  $request->email,
                    'phone'   =>  $request->phone,
                    'user_id' =>  $referer->id
                ]);
            }
            return $this->sendResponse($response, "Referring added successfully!");
        } else
            return $this->sendError('Error', 401);
    }
}
