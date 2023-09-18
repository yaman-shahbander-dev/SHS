<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Repositories\NotificationRepository;
use App\Repositories\CustomFieldRepository;
use App\DataTables\NotificationDataTable;
use App\Http\Requests\UpdateCityRequest;
use Flash;
use App\Repositories\UploadRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use App\Country;
use App\City;
use App\Models\Category;
use App\Models\User;
use App\subCategory;

class NotificationController extends Controller
{




    /** @var  SubCategoriesRepository */
    private $NotificationRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(NotificationRepository $NotificationRepo, CustomFieldRepository $customFieldRepo, UploadRepository $uploadRepo)
    {
        parent::__construct();
        $this->NotificationRepository = $NotificationRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->uploadRepository = $uploadRepo;
    }


    public function index(NotificationDataTable $notificationDataTable)
    {
        if(!auth()->user()->hasPermissionTo('notification.index')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        return $notificationDataTable->render('notifications.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasPermissionTo('notification.create')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $countries = Country::all();
        $categories = Category::all();
        return view('notifications.create',['countries' => $countries, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('notification.store')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

         $SERVER_API_KEY = 'AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];


        $countries = Country::all();
        $categories = Category::all();


        $users = User::whereNotNull('fcm_token');


        if ($request->type == 1)
            $users = $users->whereHas('roles', function($query){
                $query->where('role_id', 3);
            });


        if ($request->type == 2)
            $users = $users->whereHas('roles', function($query){
                $query->where('role_id', 4);
            });

        if ($request->country != 0) {
            $country_id = $request->country;
            if ($request->city == 0) {
                $users = $users->whereHas('cities', function($query) use ($country_id) {
                    $query->where('country_id', $country_id);
                 });
            } else {
                $users = $users->where('city_id', $request->city);
            }
        }

        if ($request->category != 0) {
            $category_id = $request->category;
            if ($request->subcategory == 0) {
                $users = $users->whereHas('subcategories', function($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                 });
            } else {
                $subcategory_id = $request->subcategory;
                $users = $users->whereHas('subcategories', function($query) use ($subcategory_id) {
                    $query->where('subcategory_id', $subcategory_id);
                 });
            }
        }

       // $users = $users->get('device_token');

        $data = [
            "registration_ids" => $users->pluck('fcm_token'),
            "notification" => [
                "title"    => $request->title,
                "body"     => $request->description,
            ]
        ];
       // return dd($users->get());

        $dataString = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        // return dd(curl_exec($ch));
        $response = curl_exec($ch);

        $input['type']         = $request->type;
        $input['country']      = $request->country !=0 ? $request->country : null;
        $input['city']         = $request->city !=0 ? $request->city : null;
        $input['category']     = $request->category !=0 ? $request->category : null;
        $input['subcategory']  = $request->subcategory !=0 ? $request->subcategory : null;
        $input['title']        = $request->title;
        $input['body']         = $request->description;

        if (Notification::create($input)){
            Flash::success(trans('lang.store_operation'));
            return view('notifications.create',['countries' => $countries, 'categories' => $categories]);
        } else {
            Flash::error(trans('lang.notification_failed'));
            return view('notifications.create',['countries' => $countries, 'categories' => $categories]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasPermissionTo('notification.edit')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $countries = Country::all();
        $categories = Category::all();


        $notification = $this->NotificationRepository->findWithoutFail($id);
        $subcategories=subCategory::where('category_id',$notification->category)->get();
        $cities=City::where('country_id',$notification->country)->get();
        if (empty($notification)) {
            Flash::error(trans('lang.notification_not_found'));
            return redirect(route('notifications.index'));
        }

        return view('notifications.edit')->with('notification', $notification)
        ->with('countries', $countries)
        ->with('categories', $categories)
        ->with('subcategories',$subcategories)
        ->with('cities',$cities);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        if(!auth()->user()->hasPermissionTo('notification.update')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $SERVER_API_KEY = 'AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq
        ';
//and
// AAAA71-LrSk:APA91bHCjcToUH4PnZBAhqcxic2lhyPS2L_Eezgvr3N-O3ouu2XC7-5b2TjtCCLGpKo1jhXJqxEEFHCdg2yoBbttN99EJ_FHI5J_Nd_MPAhCre2rTKvTeFAgS8uszd_P6qmp7NkSXmuq


        //AAAA4D63pNE:APA91bHZnOMtZp1E5zvs5hmd0mniLA2JRWQwECU5Rc-aI4cvHfENc4PuMTwNnHtFwFex11IFsdEns2ErZ05GXfn-sJVDMit8lfc5RSMTF9GHfHadBQ0OMfGA8MJ0H4DQ5t3LAl-Nx6y2

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $users = User::whereNotNull('fcm_token');


        if ($request->type == 1)
            $users = $users->whereHas('roles', function($query){
                $query->where('role_id', 3);
            });


        if ($request->type == 2)
            $users = $users->whereHas('roles', function($query){
                $query->where('role_id', 4);
            });

        if ($request->country != 0) {
            $country_id = $request->country;
            if ($request->city == 0) {
                $users = $users->whereHas('cities', function($query) use ($country_id) {
                    $query->where('country_id', $country_id);
                 });
            } else {
                $users = $users->where('city_id', $request->city);
            }
        }

        if ($request->category != 0) {
            $category_id = $request->category;
            if ($request->subcategory == 0) {
                $users = $users->whereHas('subcategories', function($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                 });
            } else {
                $subcategory_id = $request->subcategory;
                $users = $users->whereHas('subcategories', function($query) use ($subcategory_id) {
                    $query->where('subcategory_id', $subcategory_id);
                 });
            }
        }

       // $users = $users->get('device_token');

        $data = [
            "registration_ids" => $users->pluck('fcm_token'),
            "notification" => [
                "title"    => $request->title,
                "body"     => $request->description,
            ]
        ];
        //return dd($data);

        $dataString = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        //
        $response = curl_exec($ch);
        //return dd(curl_exec($ch));
        $input['type']         = $request->type;
        $input['country']      = $request->country !=0 ? $request->country : null;
        $input['city']         = $request->city !=0 ? $request->city : null;
        $input['category']     = $request->category !=0 ? $request->category : null;
        $input['subcategory']  = $request->subcategory !=0 ? $request->subcategory : null;
        $input['title']        = $request->title;
        $input['body']         = $request->description;

        if ($notification->update($input)){
            Flash::success(trans('lang.update_operation'));
            return redirect(route('notification.index'));
        } else {
            Flash::error(trans('lang.notification_failed'));
            return redirect(route('notification.edit',$notification->id));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasPermissionTo('notification.destroy')){
            return view('vendor.errors.page', ['code' => 403, 'message' => trans('lang.Right_Permission')]);
        }

        $notification = $this->NotificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            Flash::error(trans('lang.notification_not_found'));

            return redirect(route('notification.index'));
        }

        $this->NotificationRepository->delete($id);

        Flash::success(trans('lang.delete_operation'));

        return redirect(route('notification.index'));
    }

    public function getsubcategory(Request $request){
        $subCategory = subCategory::where('category_id',$request->id)
            ->get();


        return $subCategory;
    }
}
