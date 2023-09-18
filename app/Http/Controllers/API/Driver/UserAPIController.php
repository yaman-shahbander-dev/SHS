<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.05.21 at 17:25:21
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

namespace App\Http\Controllers\API\Driver;

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
use Prettus\Validator\Exceptions\ValidatorException;
use App\Country;
use App\Models\reviews;
use Validator;
use PHPMailer\PHPMailer\PHPMailer;
use App\City;
use App\Balance;

class UserAPIController extends Controller
{
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

            if($request->email){

                $this->validate($request, [

                    'email' => 'required|email',
                    'password' => 'required',
                    'fcm_token' => 'required'
                ]);
                if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                    $user = auth()->user();
                }

                $IsEmail = true;


            }

            elseif($request->phone){

                $this->validate($request, [

                    'phone' => 'required',
                    'password' => 'required',
                    'fcm_token' => 'required'
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



            $user->fcm_token = $request->input('fcm_token');
            $user->language = $request->input('lang')==null ? 'en':$request->input('lang','');

                if($user->is_verified==0) {
                    $user->activation_code = rand(100000, 999999); // activation code

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
                            $mail->Body = "<p style='color:black !important;'>Hi $user->name! <br>

Thank you for choosing Smart Home Services .<br>

Your verification code is $user->activation_code.<br>

Enter this code in our app to activate your account.<br>

If you have any questions, send us an email.<br>

if it wasn't you who submitted your email address in the first place ,<br>
well then that's messed up and  we're sorry ,<br>
Simply ignore this email and don't copy the code above,You will not receive any emails from us.<br>

We’re glad you’re here!<br>
The Smart Home Services team</p>";

                            $mail->send();
                            $user->save();




                        } catch (Exception $e) {
                            return $this->sendError('error message ', 401);
                        }
                    }
                    $response_cod=
                        ['id'=>$user->id,
                            'first_name'=>$user->name,
                            'last_name'=>$user->last_name,
                            'approved' => 1,
                            'is_verified' => false,
                            'email'=>$user->email,
                            'avatar'=>asset('storage/Avatar').'/'.$user->avatar,
                            'device_token'=>$user->device_token,
                            'phone'=>$user->phone,
                            'country'=>null,
                            'activation_code'=>$user->activation_code

                        ];


                    return $this->sendResponse($response_cod, 'user not verified');

                }

                           $user->save();

          $response=
                ['id'=>$user->id,
                    'first_name'=>$user->name,
                    'last_name'=>$user->last_name,
                    'approved' => 1,
                    'is_verified' => $user->is_verified==1?true:false,
                    'email'=>$user->email,
                    'avatar'=>asset('storage/Avatar').'/'.$user->avatar,
                    'lang'=>$user->language,
                    'device_token'=>$user->device_token,
                    'phone'=>$user->phone,

                    'country'=>[
                        'id'=>(Country::find($user->cities->country_id))->id,
                        'country_name'=>(Country::find($user->cities->country_id))->country_name,
                        'city'=>[
                            'id'=>$user->cities->id,
                            'name'=>$user->cities->city_name,
                        ]
                    ]

                ];
                $token = $request->session()->token();

                $token = csrf_token();

               ;
                return $this->sendResponse($token, 'User retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
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

            if($request->email) {

                $this->validate($request, [
                    'first_name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'fcm_token' => 'required'
                ]);
                $IsEmail = true;
                $user=User::where('email',$request->email)->first();
            }
            elseif($request->phone){
                $this->validate($request, [
                    'first_name' => 'required',
                    'phone' => 'required',
                    'password' => 'required',
                    'fcm_token' => 'required'
                ]);
                $IsEmail = false;
                $user=User::where('phone',$request->phone)->first();

            }
            if(!empty($user)){
                return $this->sendError('user is already exist!!', 401);

            }
            $user = new User;
            $user->name = $request->input('first_name');
          //  $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->fcm_token = $request->input('fcm_token');

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

            $user->language = $request->input('lang')==null ? 'en':$request->input('lang','');
            $user->phone = $request->input('phone')==null ? '':$request->input('phone','');


            $user->activation_code = rand(100000,999999); // activation code

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
           // $user->api_token = str_random(60);
            $user->save();

            $user->assignRole('homeowner');

          //  $IsEmail ? $user->notify(new \App\Notifications\VerifyEmail($user->activation_code)) : $unused = false ;

            $user->assignRole('homeowner');

            if($IsEmail) {
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
                    $mail->Body    = "<p style='color:black !important;'>Hi $user->name! <br>

Thank you for choosing Smart Home Services .<br>

Your verification code is $user->activation_code.<br>

Enter this code in our app to activate your account.<br>

If you have any questions, send us an email.<br>

if it wasn't you who submitted your email address in the first place ,<br>
well then that's messed up and  we're sorry ,<br>
Simply ignore this email and don't copy the code above,You will not receive any emails from us.<br>

We’re glad you’re here!<br>
The Smart Home Services team</p>";

                    $mail->send();

                } catch (Exception $e) {
                    return $this->sendError('error message ', 401);
                }
            } else {
                $unused = false ;
            }

            $response=
                ['id'=>$user->id,
                    'first_name'=>$user->name,
                    'last_name'=>$user->last_name,
                    'approved' => 1,
                    'is_verified' => $user->is_verified==1?true:false,
                    'email'=>$user->email,
                    'activation_cod'=>$user->activation_code,
                   // 'avatar'=>$user->avatar,
                    'lang'=>$user->language,
                    'device_token'=>$user->device_token,
                    'phone'=>$user->phone,
                    //'city'=>$user->cities->city_name,
                    'country'=>null,

                ];

            event(new UserRoleChangedEvent($user));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 401);
        }


        return $this->sendResponse($response, 'User retrieved successfully');
    }
    public function verify(Request $request){
        if($request->header('devicetoken')) {
                   try {
                $user = User::where('device_token', $request->header('devicetoken'))->first();
                if (empty($user)) {
                    return $this->sendError('User not found', 401);
                }


                $user->city_id = $request->input('city_id');
                $user->language = $request->input('lang');
                $user->avatar = 'avatar.png';
                $user->is_verified = 1;
                $user->save();
                $response =
                    ['id' => $user->id,
                        'first_name' => $user->name,
                        'approved' => 1,
                        'is_verified' => $user->is_verified==1?true:false,
                        'email' => $user->email,
                        //'activation_cod'=>$user->activation_code,
                        'avatar' => asset('storage/Avatar') . '/' . $user->avatar,
                        'lang' => $user->language,
                        'device_token' => $user->device_token,
                        'phone' => $user->phone,
                        'country'=>[
                            'id'=>(Country::find($user->cities->country_id))->id,
                            'country_name'=>(Country::find($user->cities->country_id))->country_name,
                            'city'=>[
                                'id'=>$user->cities->id,
                                'name'=>$user->cities->city_name,
                            ]
                        ]

                    ];
                event(new UserRoleChangedEvent($user));
            } catch (\Exception $e) {
                return $this->sendError('error save', 401);
            }


            return $this->sendResponse($response, 'User updated successfully');

        }
        else {
            return $this->sendError('error!', 401);
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

    public function delete(Request $request) {
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
                    $userLatitude = null;
                    $userLongitude = null;
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
                $respone[$i]['latitude'] = $attr->coordinates!=null? $attr->coordinates->latitude:null;
                $respone[$i]['longitude'] = $attr->coordinates!=null? $attr->coordinates->longitude:null;

                $respone[$i]['distance'] = $attr->coordinates!=null && $userLatitude !=null? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)),2) : null;

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
                $userLatitude = null;
                $userLongitude = null;
            }
            $HiddenColumns = ['custom_fields', 'media', 'has_media', 'pivot'];
            $attrs = $user->homeOwnerHistoryAPI->makeHidden($HiddenColumns);
            $respone = [];
            $i = 0;
            foreach ($attrs as $attr) {
                $respone[$i]['id'] = $attr->id;
                $respone[$i]['name'] = $attr->name;
                $respone[$i]['avatar'] =asset('storage/Avatar').'/'.$attr->avatar;
                $respone[$i]['last_name'] = $attr->last_name;
                $respone[$i]['description'] = $attr->description;
                $respone[$i]['rating'] = sprintf("%.1f",round((getRating($attr)/20)*2)/2);
                $respone[$i]['latitude'] = $attr->coordinates!=null? $attr->coordinates->latitude:null;
                $respone[$i]['longitude'] = $attr->coordinates!=null? $attr->coordinates->longitude:null;

                $respone[$i]['distance'] = $attr->coordinates!=null && $userLatitude !=null? round(distance(floatval($userLatitude), floatval($userLongitude), floatval($attr->coordinates->latitude), floatval($attr->coordinates->longitude)),2) :null;
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


    public function langCountryCity(Request $request) {
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
//
//                $countries = Country::all()->makeHidden($hiddenElems)->transform(function ($c) use ($arr) {
//                    $c->cities->transform(function ($c) use ($arr) {
//                        // if (in_array($c->toArray()['id'], $UserCityId))
//                        if ($c->id == $arr['UserCityId'])
//
//                            $c['check'] = 1;
//                        else
//                            $c['check'] = 0;
//
//                        return $c;
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
                        'notification'=>$user->notification==1?true:false,
                        'lang' => $user->language,
                        'city_id' => '',
                        'country_id' =>  ''

                    ];
                }
                else
                    $response = [
                        'notification'=>$user->notification==1?true:false,
                        'lang' => $user->language,
                        'city_id' => $user->city_id,
                        'country_id' =>  (Country::find($user->cities->country_id))->id

                    ];


                return $this->sendResponse($response, 'Inforamtion retrieved successfully');;

            }
        }

            catch (\Exception $e){
                return $this->sendError('something was wrong', 401);

            }

    }

    public function savelangCountryCity(Request $request) {
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
        }catch (\Exception $e){
            return $this->sendError('something was wrong', 401);

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


            if (!empty ($request->file('avatar'))) {
                $imageNameAvater = uniqid() . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('storage/Avatar'), $imageNameAvater);

                $vendor->avatar = $imageNameAvater;

                $vendor->save();


            }

            return $this->sendResponse([], 'photo Saved successfully');
        } catch (\Exception $e) {
            return $this->sendError('something was wrong', 401);

        }


    }

}
