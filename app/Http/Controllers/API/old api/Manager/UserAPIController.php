<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.08.11 at 23:04:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Http\Controllers\API\Manager;

use App\Country;
use App\Criteria\Users\DriversOfRestaurantCriteria;
use App\Events\UserRoleChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CustomFieldRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UploadRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Models\reviews;
use Validator;
use PHPMailer\PHPMailer\PHPMailer;
use App\City;
use App\Models\Day;
use App\Balance;

class UserAPIController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    private $uploadRepository;
    private $roleRepository;
    private $customFieldRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, UploadRepository $uploadRepository, RoleRepository $roleRepository, CustomFieldRepository $customFieldRepo)
    {
        $this->userRepository = $userRepository;
        $this->uploadRepository = $uploadRepository;
        $this->roleRepository = $roleRepository;
        $this->customFieldRepository = $customFieldRepo;
    }

    function login(Request $request)
    {
        $IsEmail = false;

        try {

            if ($request->email) {

                $this->validate($request, [

                    'email' => 'required|email',
                    'password' => 'required',
                ]);
                if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                    $user = auth()->user();
                }
                $IsEmail = true;


            } elseif ($request->phone) {
                $this->validate($request, [

                    'phone' => 'required',
                    'password' => 'required',
                ]);
                if (auth()->attempt(['phone' => $request->input('phone'), 'password' => $request->input('password')])) {
                    $user = auth()->user();

                }
                $IsEmail = false;

            }

//            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
//                // Authentication passed...
//                $user = auth()->user();
//                if (!$user->hasRole('homeowner')) {
//                    return $this->sendError('User not homeowner', 401);
//                }
            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }
            if (!$user->hasRole('vendor')) {
                return $this->sendError('User not Vendor', 401);
            }
//                $user->device_token = $request->header('devicetoken');
//                $user->save();
            $user->language = $request->input('lang') == null ? 'en' : $request->input('lang', '');

            if ($user->is_verified == 0) {
                $user->activation_code = rand(1000, 9999); // activation code

                //Expiration code date
                $now = time();
                $time_plus_15_minutes = $now + (15 * 60);
                $packageEndDate = date('Y-m-d H:i:s', strtotime('+15 minute'));


                $user->activation_code_exp_date = $packageEndDate;

                if ($IsEmail) {
                    require '../vendor/autoload.php'; // load Composer's autoloader

                    $mail = new PHPMailer(true); // Passing `true` enables exceptions

                    try {

                        // Mail server settings

                        //$mail->SMTPDebug = 4;
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'yamanworkshahbandar@gmail.com';
                        $mail->Password = "\$_POST!'Yamahn'!";
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('yamanworkshahbandar@gmail.com', 'Smart Home Services');
                        $mail->addAddress($user->email);
                        $mail->isHTML(true);

                        $mail->Subject = 'SHS - Verification Code';
                        $mail->Body = 'Your verification code is: ' . $user->activation_code;

                        $mail->send();
                        $user->save();

                        $response_cod =
                            ['id' => $user->id,
                                'first_name' => $user->name,
                                'email' => $user->email,
                                'is_verified' => false,
                                'avatar' => asset('storage/Avatar') . '/' . $user->avatar,
                                'device_token' => $user->device_token,
                                'phone' => $user->phone,
                                'country' => null,
                                'activation_code' => $user->activation_code

                            ];
                        return $this->sendResponse($response_cod, 'user not verified');

                    } catch (\Exception $e) {
                        return $this->sendError('error message ', 401);
                    }
                }
            }
            $user->save();

            $response =
                ['id' => $user->id,
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'is_verified' => $user->is_verified == 1 ? true : false,
                    'avatar' => asset('storage/Avatar') . '/' . $user->avatar,
                    'lang' => $user->language,
                    'device_token' => $user->device_token,
                    'phone' => $user->phone,

                    'country' => [
                        'id' => (Country::find($user->cities->country_id))->id,
                        'country_name' => (Country::find($user->cities->country_id))->country_name,
                        'city' => [
                            'id' => $user->cities->id,
                            'name' => $user->cities->city_name,
                        ]
                    ]

                ];
            return $this->sendResponse($response, 'User retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendError('error', 401);
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return
     */
    function register(Request $request)
    {

        $IsEmail = false;
        try {

            if ($request->email) {

                $this->validate($request, [
                    'first_name' => 'required',
                    'email' => 'required|unique:users|email',
                    'password' => 'required',
                ]);
                $IsEmail = true;
            } elseif ($request->phone) {
                $this->validate($request, [
                    'first_name' => 'required',
                    'phone' => 'required|unique:users',
                    'password' => 'required',
                ]);
                $IsEmail = false;
            }
            $user = new User;
            $user->name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
//            $user->city_id = $request->input('city_id');
            while(true) {
                $payment_id = '#' . rand(1000, 9999) . rand(1000, 9999);
                if (!(User::where('payment_id', $payment_id)->exists())) {
                    $user->payment_id = $payment_id;
                    $user->save();
                    break;
                } else continue;
            }

            $balance = new Balance();

            $balance->balance = 0.0;

            $balance->save();

            $user->balance_id = $balance->id;

            $user->is_verified = 0;


            $user->language = $request->input('lang') == null ? '' : $request->input('lang', '');
            $user->phone = $request->input('phone') == null ? '' : $request->input('phone', '');


//            $user->language = $request->input('lang');
            $user->activation_code = rand(1000, 9999); // activation code

            //Expiration code date
            $now = time();
            $time_plus_15_minutes = $now + (15 * 60);

            $packageEndDate = date('Y-m-d H:i:s', strtotime('+15 minute'));

            $user->activation_code_exp_date = $packageEndDate;

//            $user->avatar = $request->input('avatar');
            //$user->device_token = $request->header('devicetoken');

            //Generate a random string.
            $token = openssl_random_pseudo_bytes(16);

            $user->save();

            //Convert the binary data into hexadecimal representation.
            $token = bin2hex($user->id . $token);

            $user->device_token = $token;

            $user->password = Hash::make($request->input('password'));


//            $user->device_code = str_random(60) . time();
            // $user->api_token = str_random(60);
            $user->save();

            $user->assignRole('vendor');

            if ($IsEmail) {
                require '../vendor/autoload.php'; // load Composer's autoloader

                $mail = new PHPMailer(true); // Passing `true` enables exceptions

                try {

                    // Mail server settings

                    //$mail->SMTPDebug = 4;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'yamanworkshahbandar@gmail.com';
                    $mail->Password = "\$_POST!'Yamahn'!";
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('yamanworkshahbandar@gmail.com', 'Smart Home Services');
                    $mail->addAddress($user->email);
                    $mail->isHTML(true);

                    $mail->Subject = 'SHS - Verification Code';
                    $mail->Body = 'Your verification code is: ' . $user->activation_code;

                    $mail->send();

                } catch (Exception $e) {
                    return back()->with('error', 'Message could not be sent.');
                }
            } else {
                $unused = false;
            }

            $response =
                ['id' => $user->id,
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'activation_cod' => $user->activation_code,
                    'is_verified' => $user->is_verified == 1 ? true : false,
                    'avatar' => asset('storage/Avatar') . '/' . $user->avatar,
                    'lang' => $user->language,
                    'device_token' => $user->device_token,
                    'phone' => $user->phone,
                    'country' => null,
                    // 'country'=>(Country::find($user->cities->country_id))->country_name,

                ];

            event(new UserRoleChangedEvent($user));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }


        return $this->sendResponse($response, 'User retrieved successfully');
    }


    public function verify(Request $request)
    {
        if ($request->header('devicetoken')) {
            try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }


                $user->city_id = $request->input('city_id');
                //$user->language = $request->input('lang');
                $user->avatar = 'avatar.png';
                $user->is_verified = 1;
                $user->save();
                $response =
                    ['id' => $user->id,
                        'first_name' => $user->name,
                        'is_verified' => $user->is_verified == 1 ? true : false,
                        'email' => $user->email,
                        //'activation_cod'=>$user->activation_code,
                        'avatar' => asset('storage/Avatar') . '/' . $user->avatar,
                        'lang' => $user->language,
                        'device_token' => $user->device_token,
                        'phone' => $user->phone,
//                        'city' => $user->cities->city_name,
//                        'country' => (Country::find($user->cities->country_id))->country_name,

                    'country' => [
                        'id' => (Country::find($user->cities->country_id))->id,
                        'country_name' => (Country::find($user->cities->country_id))->country_name,
                        'city' => [
                            'id' => $user->cities->id,
                            'name' => $user->cities->city_name,
                        ]
                    ]

                ];
                event(new UserRoleChangedEvent($user));
            } catch (\Exception $e) {
                return $this->sendError('error save', 401);
            }


            return $this->sendResponse($response, 'User updated successfully');

        } else {
            return $this->sendError('error!', 401);
        }
    }

    //$request->header('devicetoken')


    public function completeRegistration(Request $request)
    {
        if ($request->header('devicetoken')) {
            $vendor = User::where('device_token', $request->header('devicetoken'))->first();
            if (!empty($vendor)) {

                $vendor->subcategories()->sync($request->subcategories);
                $vendor->days()->sync($request->days);
                return $this->sendResponse([], 'Data saved successfully');
            } else {
                return $this->sendResponse([], 'User not found');
            }
        } else {
            return $this->sendResponse([], 'Error');
        }
    }

    function logout(Request $request)
    {
        $user = $this->userRepository->findByField('api_token', $request->input('api_token'))->first();
        if (!$user) {
            return $this->sendError('User not found', 401);
        }
        try {
            auth()->logout();
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        return $this->sendResponse($user['name'], 'User logout successfully');

    }

    function user(Request $request)
    {
        $hiddenColumns = ['custom_fields', 'has_media', 'media'];

        $user = $this->userRepository->where('api_token', $request->input('api_token'))->get(['id', 'name', 'avatar', 'description', 'last_name']);

        $user->makeHidden($hiddenColumns);

        if (!$user) {
            return $this->sendError('User not found', 401);
        }

        return $this->sendResponse($user, 'User retrieved successfully');
    }

    function settings(Request $request)
    {
        $settings = setting()->all();
        $settings = array_intersect_key($settings,
            [
                'default_tax' => '',
                'default_currency' => '',
                'default_currency_decimal_digits' => '',
                'app_name' => '',
                'currency_right' => '',
                'enable_paypal' => '',
                'enable_stripe' => '',
                'enable_razorpay' => '',
                'main_color' => '',
                'main_dark_color' => '',
                'second_color' => '',
                'second_dark_color' => '',
                'accent_color' => '',
                'accent_dark_color' => '',
                'scaffold_dark_color' => '',
                'scaffold_color' => '',
                'google_maps_key' => '',
                'mobile_language' => '',
                'app_version' => '',
                'enable_version' => '',
                'distance_unit' => '',
            ]
        );

        if (!$settings) {
            return $this->sendError('Settings not found', 401);
        }

        return $this->sendResponse($settings, 'Settings retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     *
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }
        $input = $request->except(['password', 'api_token']);
        try {
            if ($request->has('device_token')) {
                $user = $this->userRepository->update($request->only('device_token'), $id);
            } else {
                $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->userRepository->model());
                $user = $this->userRepository->update($input, $id);

                foreach (getCustomFieldsValues($customFields, $request) as $value) {
                    $user->customFieldsValues()
                        ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
                }
            }
        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 401);
        }

        return $this->sendResponse($user, __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(true, 'Reset link was sent successfully');
        } else {
            return $this->sendError([
                'error' => 'Reset link not sent',
                'code' => 401,
            ], 'Reset link not sent');
        }

    }


    public function delete(Request $request)
    {
        $id = $request->id;

        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendResponse([
                'error' => true,
                'code' => 404,
            ], 'User not found');
        }

        $this->userRepository->delete($id);

        return $this->sendResponse(true, 'User Deleted Successfully');
    }

    /**
     * Display a listing of the Drivers.
     * GET|HEAD /restaurants
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function driversOfRestaurant($id, Request $request)
    {
        try {
            $this->userRepository->pushCriteria(new RequestCriteria($request));
            $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->userRepository->pushCriteria(new DriversOfRestaurantCriteria($id));
            $users = $this->userRepository->all();

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse($users->toArray(), 'Drivers retrieved successfully');
    }

    public function myReviews(Request $request) {
        //$id = $request->id; // logged in user ID
        if($request->header('devicetoken')) {
            try{
            $user = User::where('device_token', $request->header('devicetoken'))->first();
            if (empty($user)) {
                return $this->sendError('User not found', 401);

            }
                try{
            $userLatitude = $user->coordinates->latitude;
            $userLongitude = $user->coordinates->longitude;
                }
                catch (\Exception $e){
                   // return $this->sendError('You have to turn on gps', 401);

                }
            $reviewsHiddenColumns = ['custom_fields', 'media', 'has_media'];
            $attrs = $user->vendorsAPI->makeHidden($reviewsHiddenColumns); // vendors I reviewd
            $respone = [];
            $i = 0;
            foreach ($attrs as $attr) {
                $respone[$i]['id'] = $attr->id;
                $respone[$i]['name'] = $attr->name;
                $respone[$i]['avatar'] =asset('storage/Avatar').'/'.$attr->avatar;
                $respone[$i]['last_name'] = $attr->last_name;
                $respone[$i]['description'] = $attr->pivot->description;
                $respone[$i]['rating'] = sprintf("%.1f",round((myReviewRating($attr)/20)*2)/2);
                $respone[$i]['latitude'] = $attr->coordinates!=null? $attr->coordinates->latitude:'No coordinates provided for the current vendor';
                $respone[$i]['longitude'] = $attr->coordinates!=null? $attr->coordinates->longitude:'No coordinates provided for the current vendor';

                $respone[$i]['distance'] = $attr->coordinates!=null && $userLatitude !=null? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)),2) : 'No coordinates provided for the current vendor';

                //  $respone[$i]['distance'] = $attr->coordinates ? distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)) : 'No coordinates provided for the current vendor';
                $i++;
            }

            return $this->sendResponse($respone, 'reviews retrieved successfully');
            }
            catch (\Exception $e){
                return $this->sendError('Error', 401);

            }
        }
        else
            return $this->sendError('You dont have permission', 401);

    }



    public function history(Request $request)
    {
        try {

        if ($request->header('devicetoken')) {
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
            $attrs = $user->homeOwnerHistoryAPI->makeHidden($HiddenColumns);
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

                $respone[$i]['distance'] = $attr->coordinates!=null && $userLatitude !=null? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)),2) : 'No coordinates provided for the current vendor';
                $i++;
            }
            return $this->sendResponse($respone, 'history retrieved successfully');
        } else
            return $this->sendError('Error!', 401);
        }
        catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }

    }



    public function backgroundPic(Request $request)
    {
        try {
            if (empty($request->header('devicetoken'))) {
                return $this->sendError('device token not found', 401);
            }

            $vendor = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($vendor)) {
                return $this->sendError('User not found', 401);
            }

            if (!empty ($request->file('background_profile'))) {

                $imageName = uniqid() . $request->file('background_profile')->getClientOriginalName();

                $request->file('background_profile')->move(public_path('storage/vendors_background'), $imageName);

                $vendor->background_profile = $imageName;

                $vendor->save();

                //
            }
            if (!empty ($request->file('avatar'))) {
                $imageNameAvater = uniqid() . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('storage/Avatar'), $imageNameAvater);

                $vendor->avatar = $imageNameAvater;

                $vendor->save();


            }
            $vendor->save();

            return $this->sendResponse([], 'photo Saved successfully');
        } catch (\Exception $e) {
            return $this->sendError('something was wrong', 401);

        }


    }

    // 'reviews'         => ($vendor->clientsAPI)->transform(function($q){
    //     return $q->select(['name', 'description'])->get();
    // })

    /**public function history(Request $request) {
        if($request->header('devicetoken')) {

            $user = User::where('device_token', $request->header('devicetoken'))->first();

            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }

            $transactionsHistory = $user->ToUserName->transform(function($q){


            });

            return $this->sendResponse($transactionsHistory->toArray(), 'History retrieved successfully');
        }

        */

    public function vendorprofile(Request $request)
    { //for vendor screens
        try {


            if (empty($request->header('devicetoken'))) {
                return $this->sendError('device token not found', 401);
            }
            $vendor = User::where('device_token', $request->header('devicetoken'))->first();
            if (empty($vendor)) {
                return $this->sendError('User not found', 401);
            }
            $response = [];
            $response = [
                'avatar' =>asset('storage/Avatar').'/'.$vendor->avatar,
                'background' =>asset('storage/vendors_background').'/'.($vendor->background_profile==null?'background.jpg':$vendor->background_profile),
                'first_name' => $vendor->name,
                'status' => $vendor->status->only('id', 'status_type'),
                'rating' => sprintf("%.1f",round((getRating($vendor) / 20) * 2) / 2),
                'count_reviews' => count($vendor->clients),
                'count_contacted' => count($vendor->userContacts),
                'count_views' => count($vendor->userViews),
                'count_favorites' => count($vendor->homeOwnerFavorite),
                'reviews' => ($vendor->clientsAPI)->transform(function ($q) {
                    $review = reviews::find($q->pivot->id);
                    return $q = [
                        'id' => $q->pivot->id,
                        'name' => $q->name,
                        'rating' => sprintf("%.1f",round((getFullRating($review) / 20) * 2) / 2),
                        'description' => $q->pivot->description,
                        'avatar' => asset('storage/Avatar') . '/' . $q->avatar,

                    ];
                }),
                'offers' => $vendor->specialOffers->transform(function ($q) {
                    return $q = [
                        'id' => $q->id,
                        'title' => $q->title,
                        'description' => $q->description,
                        'image' => asset('storage/specialOffersPic') . '/' . $q->image,
                    ];
                }),
            ];
            return $this->sendResponse($response, 'User retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendError('something was wrong', 401);

        }

    }


    public function langCountryCity(Request $request)
    {
        try {

            if ($request->header('devicetoken')) {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }
//
//                $hiddenElems = ['created_at', 'updated_at', 'name_en'];
//                $arr = [
//                    'UserCityId' => $user->city_id,
//                    'UserCountryId' => (Country::find($user->cities->country_id))->id
//                ];
//                //return dd(Country::with('City')->get());
//
//                $countries = Country::all()->makeHidden($hiddenElems)->transform(function ($c) use ($arr) {
//                    $c->City->transform(function ($c) use ($arr) {
//                        // if (in_array($c->toArray()['id'], $UserCityId))
//                        if ($c->id == $arr['UserCityId'])
//
//                            $c['check'] = 1;
//                        else
//                            $c['check'] = 0;
//
//                        return $c->only('id', 'city_name', 'check');
//                    });
//
//                    if ($c->id == $arr['UserCountryId'])
//
//                        $c['check'] = 1;
//                    else
//                        $c['check'] = 0;
//
//
//                    return $c;
//                });
                if($user->city_id==null){
                    $response = [
                        'notification'=> $user->notification,
                        'lang' => $user->language,
                        'city_id' => '',
                        'country_id' =>  ''

                    ];
                }
                else
                $response = [
                    'notification'=> $user->notification,
                    'lang' => $user->language,
                    'city_id' => $user->city_id,
                    'country_id' =>  (Country::find($user->cities->country_id))->id

                ];

                return $this->sendResponse($response, 'Inforamtion retrieved successfully');;

            }
        } catch (\Exception $e) {
            return $this->sendError('something was wrong', 401);

        }
    }

    public function savelangCountryCity(Request $request)
    {
        try {

            if ($request->header('devicetoken')) {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $user->notification=$request->notification;
                $user->city_id=$request->city_id;
                $user->language=$request->lang;
                $user->save();

                return $this->sendResponse([], 'Inforamtion saved successfully');;
            }
        } catch (\Exception $e) {
            return $this->sendError('something was wrong', 401);

        }
    }

    public function updatevendorstatus(Request $request)
    {
        try {

            if ($request->header('devicetoken')) {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $user->status_id = $request->status_id;
                $user->save();

                return $this->sendResponse($user->toArray(), 'Inforamtion saved successfully');;
            }
        } catch (\Exception $e) {
            return $this->sendError('something was wrong', 401);

        }
    }

    public function getcategorySubcatory(Request $request)
    {
        if ($request->header('devicetoken')) {
            $user = User::where('device_token', $request->header('devicetoken'))->first();
            if (empty($user)) {
                return $this->sendError('User not found', 401);
            }


            $user1 = User::where('device_token', $request->header('devicetoken'))->first();

            $sub = $user->subcategories->transform(function ($q) {
                return $q->id;
            });

            $user1 = $user1->subcategories->transform(function ($q) use ($sub) {
                $arr = $q->categories->subCategory->transform(function ($s) use ($sub) {
                    if (in_array($s->id, $sub->toArray()))
                        return $s->only('id', 'name');

                });


                $q['id'] = $q->categories->id;
                $q['name'] = $q->categories->name;

                $q['subcategories'] = $arr->filter(function ($value) {
                    return $value != null;
                });;

                return $q->only('id', 'name', 'subcategories');
            })->unique('id');
            return $this->sendResponse($user1, 'Inforamtion saved successfully');;
        }
    }

    public function vendorReply(Request $request)
    {
        try {
            if ($request->header('devicetoken')) {

                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $review = reviews::find($request->review_id);

                $review->reply = $request->message;

                $review->save();

                return $this->sendResponse($request->message, "Reply added successfully!");
            }
        } catch (\Exception $e) {
            return $this->sendError('somthing was wrong', 401);

        }
    }

    public function vendorReplyInfo(Request $request)
    {
        try {
            if ($request->header('devicetoken')) {

                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }

                $review = reviews::find($request->review_id);

                $response = [
                    'price_rating' => sprintf("%.1f",round(($review->price_rating / 20) * 2) / 2),
                    'service_rating' => sprintf("%.1f",round(($review->service_rating / 20) * 2) / 2),
                    'speed_rating' => sprintf("%.1f",round(($review->speed_rating / 20) * 2) / 2),
                    'trust_rating' => sprintf("%.1f",round(($review->trust_rating / 20) * 2) / 2),
                    'knowledge_rating' => sprintf("%.1f",round(($review->knowledge_rating / 20) * 2) / 2),
                    'reply' => $review->reply,
                ];

                return $this->sendResponse($response, "Reply retrieved successfully!");
            }
        } catch (\Exception $e) {
            return $this->sendError('somthing was wrong', 401);

        }
    }

    public function supportedDays(Request $request)
    {
        try {
            $lang = false;
            if ($request->header('devicetoken')) {
                $vendor = User::where('device_token', $request->header('devicetoken'))->first();
                $supported = $vendor->days->transform(function ($q) {
                    return $q->id;
                });

                $arr = [
                    'supported' => $supported->toArray(),
                    'vendor_id' => $vendor->id,
                    'lang' => $vendor->language
                ];

                $days = Day::all()->transform(function ($days) use ($arr) {
                    if (in_array($days->id, $arr['supported'])) {
                        $days['check'] = 1;
                        $days['workHours'] = [
                            'start' => date("g:i A",strtotime($days->Vendordays->where('id', $arr['vendor_id'])->first()->pivot->start)),
                            'end' => date("g:i A",strtotime($days->Vendordays->where('id', $arr['vendor_id'])->first()->pivot->end)),
                        ];
                    } else
                        $days['check'] = 0;
                    $days['name'] = $days['name_' . $arr['lang']];

                    return $days->only('id', 'name', 'check', 'workHours');
                });

                return $this->sendResponse($days, "Supported retrieved successfully!");
            }
        } catch (\Exception $e) {
            return $this->sendError('somthing was wrong', 401);

        }
    }

    public function supportedDaysUpdate(Request $request)
    {
        try {
            if ($request->header('devicetoken')) {
                $vendor = User::where('device_token', $request->header('devicetoken'))->first();
                if (!empty($vendor)) {
                    $time_in_24_hour_format  = date("H:i", strtotime("1:30 PM"));
$days=[];

                    foreach ($request->days as $day){
                       // return $day['day_id'];

                        $days[]=[
          'day_id'=>$day['day_id'],
          'start'=>date("H:i", strtotime($day['start'])),
          'end'=>date("H:i", strtotime($day['end'])),
               ];
                    }

                    $vendor->days()->sync($days);
                    return $this->sendResponse([], 'Data saved successfully');
                } else {
                    return $this->sendError('User not found', 401);
                }
            } else {
                return $this->sendResponse([], 'Error');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);

        }
    }

    public function getVendorCities(Request $request) {
        try {
            if ($request->header('devicetoken')) {

                $vendor = User::where('device_token', $request->header('devicetoken'))->first();

                if (empty($vendor)) {
                    return $this->sendError('User not found', 401);
                }

                $existedCitiesIds = $vendor->vendor_city->transform(function($q) {
                    return  $q['id']; });

                $response['cities_id'] =  $existedCitiesIds->toArray();

                return $this->sendResponse($response, 'Cities retrieved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Something is wrong', 401);
        }
    }

    public function addVendorCities(Request $request) {
        try {

          $vendor = User::where('device_token', $request->header('devicetoken'))->first();

          if (empty($vendor)) {
            return $this->sendError('User not found', 401);
          }

          $vendor->vendor_city()->sync($request->cities);

          return $this->sendResponse([], 'Cities added successfully');

        } catch (\Exception $e) {
            return $this->sendError('Something is wrong', 401);
        }
    }

}

